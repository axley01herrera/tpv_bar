<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Product extends BaseController
{
    protected $objSession;

    function  __construct()
    {
        $this->objSession = session();
    }
    
    public function index()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['menu_ative'] = 'product';
        $data['page'] = 'admin/product/index';

        return view('admin/layout/header', $data);
    }

    public function showModalProduct()
    {
        $data = array();
        $data['action'] = $this->request->getPost('action');

        if($data['action'] == 'create') // CREATE
        {
            $data['title'] = 'Nuevo Producto';
            return view('admin/modals/product', $data);
        }
    }

    public function showModalCat()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['action'] = $this->request->getPost('action');

        if($data['action'] == 'create') // CREATE
        {
            $data['title'] = 'Nueva Categoría de Producto';
            return view('admin/modals/cat', $data);
        }
    }

    public function createCat()
    {
        $response = array();

        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
        {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;

        $data = array();
        $data['name'] = trim(preg_replace("/[^A-Za-z0-9 ]/", "", $this->request->getPost('cat')));

        $result = $objProductModel->objCreate('category', $data);

        if($result['error'] == 0)
        {
            $response['error'] = 0;
            $response['msg'] = 'Categoría Creada';
        }
        else
        {
            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error';
        }

        return json_encode($response);
    }
}
