<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TpvModel;

class TPV extends BaseController
{
    protected $objSession;

    function  __construct()
    {
        $this->objSession = session();
    }

    public function index()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')))
            return view('logout');

        $obTpvModel = new TpvModel;
        $table = $obTpvModel->getOpenTables();

        $data = array();
        $data['table'] = $table;
        $data['countTable'] = sizeof($table);

        return view('tpv/main', $data);
    }

    public function openTable()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')))
        {
            $response = array();
            $response['error'] = 2;
            
            return json_encode($response);
        }

        $tableID = $this->request->getPost('table');

        $data = array();
        $data['fkEmployee'] = $this->objSession->get('user')['id'];
        $data['tableID'] = $tableID;
        $data['dateOpen'] = date('Y-m-d');

        $obTpvModel = new TpvModel;
        $result = $obTpvModel->objCreate('tables', $data);

        return json_encode($result);
    }

    public function tpv()
    {

    }

}