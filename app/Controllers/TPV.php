<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\TpvModel;

class TPV extends BaseController
{
    protected $objSession;
    public $objAdminModel;
    public $objTpvModel;

    function  __construct()
    {
        $this->objSession = session();
        $this->objAdminModel = new AdminModel;
        $this->objTpvModel = new TpvModel;
    }

    public function index()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $obTpvModel = new TpvModel;
        $table = $obTpvModel->getOpenTables();
        $config = $this->objAdminModel->getConfigData();
        $countConfig = sizeof($config);

        $hall = 0;
        $terrace = 0;

        for ($i = 0; $i < $countConfig; $i++) {

            if ($config[$i]->type == 'hall')
                $hall = $config[$i]->valueNumber;
            elseif ($config[$i]->type == 'terrace')
                $terrace = $config[$i]->valueNumber;
        }

        $data = array();
        $data['table'] = $table;
        $data['countTable'] = sizeof($table);
        $data['user'] = $this->objSession->get('user');
        $data['hall'] = (int) $hall;
        $data['terrace'] = (int) $terrace;

        return view('tpv/main', $data);
    }

    public function tableHistory()
    {
        return view('tpv/tableHistory');
    }

    public function openTable()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {
            $response = array();
            $response['error'] = 2;

            return json_encode($response);
        }

        $table = $this->request->getPost('table');

        $data = array();
        $data['fkEmployee'] = $this->objSession->get('user')['id'];
        $data['tableID'] = $table;
        $data['dateOpen'] = date('Y-m-d H:i:s');
        $data['date'] = date('Y-m-d');

        $result = $this->objTpvModel->objCreate('tpv_bar_tables', $data);

        return json_encode($result);
    }

    public function tpv()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $tableID = $this->request->uri->getSegment(3);
        $category = $this->objAdminModel->getCategories();
        $products = $this->objAdminModel->getActiveProducts();
        $tableInfo = $this->objTpvModel->getTables($tableID);
        $ticket = $this->objTpvModel->getTicketByTable($tableID);

        $data = array();
        $data['tableID'] = $tableID;
        $data['tableInfo'] = $tableInfo;
        $data['category'] = $category;
        $data['countCategory'] = sizeof($category);
        $data['products'] = $products;
        $data['countProducts'] = sizeof($products);
        $data['ticket'] = $ticket;
        $data['countTicket'] = sizeof($ticket);

        return view('tpv/tpv', $data);
    }

    public function getProductsbyCat()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $catgoryID = $this->request->getPost('catgoryID');
        $result = $this->objAdminModel->getProductData(null, $catgoryID);

        $data = array();
        $data['products'] = $result;
        $data['countProducts'] = sizeof($result);

        return view('tpv/tpvProducts', $data);
    }

    public function createTicket()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['fkEmployee'] = $this->objSession->get('user')['id'];
        $data['fkTable'] = $this->request->getPost('tableID');
        $data['fkProduct'] = $this->request->getPost('productID');

        $objTpvModel = new TpvModel;
        $result = $objTpvModel->objCreate('tpv_bar_ticket', $data);

        if ($result['error'] == 0) {

            $ticket = $objTpvModel->getTicketByTable($data['fkTable']);
            $data['ticket'] = $ticket;
            $data['countTicket'] = sizeof($ticket);

            return view('tpv/tpvTicket', $data);
        }
    }

    public function deleteTicket()
    {
        $ticketID = $this->request->getPost('ticketID');

        $objTpvModel = new TpvModel;
        $result = $objTpvModel->objDelete('tpv_bar_ticket', $ticketID);

        if ($result === true) {

            $tableID = $this->request->getPost('tableID');
            $ticket = $objTpvModel->getTicketByTable($tableID);
            $data['ticket'] = $ticket;
            $data['countTicket'] = sizeof($ticket);

            return view('tpv/tpvTicket', $data);
        }
    }

    public function showModalChargeType()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');
        
        $data = array();
        $data['title'] = 'Tipo de Pago';
        $data['tableID'] = $this->request->getPost('tableID');
        $data['amount'] = $this->request->getPost('amount');

        return view('tpv/modalChargeType', $data);
    }

    public function chargeTable()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {
            $response = array();
            $response['error'] = 2;

            return json_encode($response);
        }

        $id = $this->request->getPost('tableID');

        $data = array();
        $data['amount'] = $this->request->getPost('amount');
        $data['payType'] = $this->request->getPost('payType');
        $data['status'] = 0;
        $data['dateClose'] = date('Y-m-d H:i:s');

        $result = $this->objTpvModel->objUpdate('tpv_bar_tables', $data, $id);

        if ($result['error'] == 0) { // SUCCESS UPDATE RECORD

            $response['error'] = 0;
            $response['msg'] = 'Mesa Cobrada';
        } else { // ERROR UPDATE RECORD

            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error';
        }

        return json_encode($response);
    }
}
