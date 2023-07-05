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
        if(empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $obTpvModel = new TpvModel;
        $table = $obTpvModel->getOpenTables();

        $data = array();
        $data['table'] = $table;
        $data['countTable'] = sizeof($table);
        $data['user'] = $this->objSession->get('user');

        return view('tpv/main', $data);
    }

    public function openTable()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
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
        # VERIFY SESSION
        if(empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $objProductModel = new ProductModel;
        $objTpvModel = new TpvModel;
        $tableID = $this->request->uri->getSegment(3);
        $category = $objProductModel->getCategories();
        $products = $objProductModel->getProducts();
        $tableInfo = $objTpvModel->getTables($tableID);

        $data = array();
        $data['tableID'] = $tableID;
        $data['tableInfo'] = $tableInfo;
        $data['category'] = $category;
        $data['countCategory'] = sizeof($category);
        $data['products'] = $products;
        $data['countProducts'] = sizeof($products);

        return view('tpv/tpv', $data);
    }

    public function getProductsbyCat()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $objProductModel = new ProductModel;
        $catgoryID = $this->request->getPost('catgoryID');
        $result = $objProductModel->getProductData(null, $catgoryID);

        $data = array();
        $data['products'] = $result;
        $data['countProducts'] = sizeof($result);

        return view('tpv/tpvProducts', $data);
    }

}