<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
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

        $objCategoryModel = new CategoryModel;

        $data = array();
        $data['nameCat'] = trim(preg_replace("/[^A-Za-z0-9 ]/", "", $this->request->getPost('nameCat')));

        $result = $objCategoryModel->objCreate('category', $data);

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

    public function updateCat()
    {
        $response = array();

        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
        return view('logoutAdmin');   

        $nameCat = $this->request->getPost('nameCat');
        $id = $this->request->getPost('catID');

        $objModel = new CategoryModel;
        $result_checkCatExist = $objModel->checkCatExist($nameCat, $id);

        if (empty($result_checkCatExist)) {
            $data = array();
            $data['nameCat'] = $nameCat;
            $data['catID'] = $this->request->getPost('catID');

            $result_update = $objModel->updateCat($data, $id);

            if ($result_update['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Categoría Actualizado';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ya existe una Categoría con el mismo nombre';
        }

        return json_encode($response);
    }
}