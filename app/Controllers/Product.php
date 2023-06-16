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

    public function createProduct()
    {
        $response = array();

        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
        return view('logoutAdmin');    

        $data = array();
        $data['name'] = trim($this->request->getPost('name'));
        $data['cat'] = trim($this->request->getPost('cat'));
        $data['price'] = trim($this->request->getPost('price'));
        $data['description'] = trim($this->request->getPost('description'));

        $objModel = new ProductModel;
        $resultCheckUserExist = $objModel->checkProductExist($data['name']);

        if (empty($resultCheckUserExist)) {
            $result = $objModel->objCreate('product', $data);

            if ($result['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Producto creado';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {
            $response['error'] = 3;
            $response['msg'] = 'Ya existe un producto con el mismo nombre';
        }

        return json_encode($response);
    }

    public function updateProduct()
    {
        $response = array();

        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
        return view('logoutAdmin');   

        $name = $this->request->getPost('name');
        $id = $this->request->getPost('userID');

        $objModel = new ProductModel;
        $result_checkProductExist = $objModel->checkProductExist($name, $id);

        if (empty($result_checkProductExist)) {
            $data = array();
            $data['name'] = $name;
            $data['cat'] = $this->request->getPost('cat');
            $data['price'] = $this->request->getPost('price');
            $data['description'] = $this->request->getPost('description');

            $result_update = $objModel->updateProduct($data, $id);

            if ($result_update['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Producto Actualizado';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ya existe un producto con el mismo nombre';
        }

        return json_encode($response);
    }

    public function deleteProduct()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
        return view('logoutAdmin');  

        $id = $this->request->getPost('userID');

        $objModel = new ProductModel;
        $result = $objModel->deleteProduct($id);

        if ($result == true) {
            $response['error'] = 0;
            $response['msg'] = 'Producto Eliminado';
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    public function changeProductStatus()
    {
        $response = array();

        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['status'] = $this->request->getPost('status');

        $objModel = new ProductModel;
        $result = $objModel->updateProduct($data, $this->request->getPost('userID'));

        if ($result['error'] == 0) {
            $msg = '';

            if ($data['status'] == 0)
                $msg = 'Producto Desactivado';
            elseif ($data['status'] == 1)
                $msg = 'Producto Activado';

            $response['error'] = 0;
            $response['msg'] = $msg;
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    public function processingProduct()
    {
        $dataTableRequest = $_REQUEST;

        $params = array();
        $params['draw'] = $dataTableRequest['draw'];
        $params['start'] = $dataTableRequest['start'];
        $params['length'] = $dataTableRequest['length'];
        $params['search'] = $dataTableRequest['search']['value'];
        $params['sortColumn'] = $dataTableRequest['order'][0]['column'];
        $params['sortDir'] = $dataTableRequest['order'][0]['dir'];

        $row = array();
        $totalRecords = 0;

        $objModel = new ProductModel;
        $result = $objModel->getProductProcessingData($params);
        $totalRows = sizeof($result);

        

        for ($i = 0; $i < $totalRows; $i++) {
            $status = '';
            $switch = '';

            if ($result[$i]->status == 1) {
                $status = '<span class="badge bg-success">En Venta</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2">
                                                <input data-id="' . $result[$i]->id . '" data-status="' . $result[$i]->status . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" checked />
                                            </div>';
            } else {
                $status = '<span class="badge bg-danger">Agotado</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2">
                                                <input data-id="' . $result[$i]->id . '" data-status="' . $result[$i]->status . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" />
                                            </div>';
            }


            $btn_edit = '<button class="ms-1 me-1 btn btn-sm btn-warning btn-edit-product" data-id="' . $result[$i]->id . '"><span class="mdi mdi-account-edit-outline" title="Editar Producto"></span></button>';
            $btn_delete = '<button class="ms-1 me-1 btn btn-sm btn-danger btn-delete-product" data-id="' . $result[$i]->id . '"><span class="mdi mdi-delete" title="Eliminar Producto"></span></button>';

            $col = array();
            $col['name'] = '<a href="' . base_url('Product/product') . '/' . $result[$i]->id . '">' . $result[$i]->name . '</a>';
            $col['cat'] = $result[$i]->cat;
            $col['price'] = $result[$i]->price;
            $col['description'] = $result[$i]->description;
            $col['status'] = $status;
            $col['switch'] = $switch;
            $col['action'] = $btn_edit . $btn_delete;

            $row[$i] =  $col;
        }

        if ($totalRows > 0)
            $totalRecords = $objModel->getTotalProduct();

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        

        return json_encode($data);
    }

    public function product()
    {
        # VERIFY SESSION
        if(empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $userID = (int) $this->request->uri->getSegment(3);

        $objModel = new ProductModel;
        $product = $objModel->getProductData($userID);

        $data['product'] = $product;

        return view('layout/header', $data);
    }

    public function showModalProduct()
    {
        $data = array();
        $data['action'] = $this->request->getPost('action');

        if ($data['action'] == 'create') {
            $data['title'] = 'Nuevo Producto';
        } elseif ($data['action'] == 'update') {
            $objModel = new ProductModel;
            $result = $objModel->getProductData($this->request->getPost('userID'));
            $data['title'] = 'Actualizando ' . $result[0]->name;
            $data['user_data'] = $result;
        }

        return view('admin/modals/product', $data);
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
