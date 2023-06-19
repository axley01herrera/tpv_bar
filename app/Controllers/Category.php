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