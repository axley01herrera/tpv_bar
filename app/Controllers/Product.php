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
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['menu_ative'] = 'product';
        $data['page'] = 'admin/product/index';

        return view('admin/layout/header', $data);
    } // OK

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

        $objProductModel = new ProductModel;
        $result = $objProductModel->getProductProcessingData($params);

        $totalRows = sizeof($result);

        for ($i = 0; $i < $totalRows; $i++) {
            $switch = '';
            $status = '';
            if ($result[$i]->statusID == 1) {
                $status = '<span class="badge bg-success">' . $result[$i]->productStatus . '</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2" title="desactivar/activar" >
                                                <input data-id="' . $result[$i]->productID . '" data-status="' . $result[$i]->statusID . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" checked />
                                            </div>';
            } else {
                $status = '<span class="badge bg-danger">' . $result[$i]->productStatus . '</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2" title="desactivar/activar" >
                                                <input data-id="' . $result[$i]->productID . '" data-status="' . $result[$i]->statusID . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" />
                                            </div>';
            }

            $btn_edit = '<button class="ms-1 me-1 btn btn-sm btn-warning btn-edit-product" data-id="' . $result[$i]->productID . '" cat-id=""><span class="mdi mdi-square-edit-outline" title="Editar Producto"></span></button>';

            $col = array();
            $col['productName'] = $result[$i]->productName;
            $col['category'] = $result[$i]->categoryName;
            $col['price'] = '€ ' . number_format($result[$i]->productPrice, 2, ".", ',');
            $col['description'] = $result[$i]->productDescription;
            $col['status'] = $status;
            $col['switch'] = $switch;
            $col['action'] = $btn_edit;

            $row[$i] =  $col;
        }

        if ($totalRows > 0) {

            if(empty($params['search']))
                $totalRecords = $objProductModel->getTotalProduct();
            else
                $totalRecords = $totalRows;
        }

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    } // OK

    public function catDataTable()
    {
        $objProductModel = new ProductModel;
        $result = $objProductModel->getCategories();

        $totalRows = sizeof($result);

        $row = array();
        $totalRecords = 0;

        for ($i = 0; $i < $totalRows; $i++) {

            $btn_edit = '<button class="ms-1 me-1 btn btn-sm btn-warning btn-edit-cat" data-id="' . $result[$i]->id . '"><span class="mdi mdi-square-edit-outline" title="Editar Producto"></span></button>';


            $col = array();
            $col['category'] = $result[$i]->name;
            $col['action'] = $btn_edit;

            $row[$i] =  $col;
        }

        if ($totalRows > 0)
            $totalRecords = $totalRows;

        $data = array();
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    } // OK

    public function showModalProduct()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $objProductModel = new ProductModel();

        $data = array();
        $data['action'] = $this->request->getPost('action');
        $data['categories'] = $objProductModel->getCategories();
        $data['countCategories'] = sizeof($data['categories']);

        if ($data['action'] == 'create')
            $data['title'] = 'Nuevo Producto';
        elseif ($data['action'] == 'update') {
            $data['product'] = $objProductModel->getProductData($this->request->getPost('productID'));
            $data['title'] = 'Actualizando ' . $data['product'][0]->name;
        }

        return view('admin/modals/product', $data);
    } // OK

    public function createProduct()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash'])) {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Espirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;

        $data = array();
        $data['name'] = trim($this->request->getPost('productName'));
        $data['price'] = trim($this->request->getPost('productPrice'));
        $data['description'] = trim($this->request->getPost('productDescription'));
        $data['fk_category'] = $this->request->getPost('categoryID');

        $resultCheckProductExist = $objProductModel->checkProductExist($data['name']);

        if (empty($resultCheckProductExist)) {

            $result = $objProductModel->objCreate('product', $data);

            if ($result['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Producto Creado';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {
            $response['error'] = 3;
            $response['msg'] = 'Ya existe el producto';
        }

        return json_encode($response);
    } // OK

    public function updateProduct()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash'])) {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Espirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;

        $name = trim($this->request->getPost('productName'));
        $id = $this->request->getPost('productID');

        $resultCheckProductExist = $objProductModel->checkProductExist($name, $id);

        if (empty($resultCheckProductExist)) {
            $data = array();
            $data['name'] = $name;
            $data['price'] = trim($this->request->getPost('productPrice'));
            $data['description'] = trim($this->request->getPost('productDescription'));
            $data['fk_category'] = $this->request->getPost('categoryID');

            $result = $objProductModel->objUpdate('product', $data, $id);

            if ($result['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Producto Actualizado';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ya existe el producto';
        }

        return json_encode($response);
    } // OK

    public function showModalCat()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['action'] = $this->request->getPost('action');

        if ($data['action'] == 'create') // CREATE
            $data['title'] = 'Nueva Categoría de Producto';
        elseif ($data['action'] == 'update') {
            $objProductModel = new ProductModel;
            $data['category'] = $objProductModel->getCatData($this->request->getPost('categoryID'));
            $data['title'] = 'Editando Categoría ' . $data['category'][0]->name;
        }

        return view('admin/modals/cat', $data);
    } // OK

    public function createCat()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash'])) {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;
        $categoryName = trim($this->request->getPost('categoryName'));
        $resultCheckCatExist = $objProductModel->checkCattExist($categoryName);

        if (empty($resultCheckCatExist)) {

            $data = array();
            $data['name'] = $categoryName;

            $result = $objProductModel->objCreate('category', $data);

            if ($result['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Categoría Creada';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else {

            $response['error'] = 1;
            $response['msg'] = 'Ya existe la categoría';
        }

        return json_encode($response);
    } // OK

    public function updateCat()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash'])) {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;
        $categoryID = trim($this->request->getPost('categoryID'));
        $categoryName = trim($this->request->getPost('categoryName'));
        $resultCheckCatExist = $objProductModel->checkCattExist($categoryName, $categoryID);

        if (empty($resultCheckCatExist)) {

            $data = array();
            $data['name'] = $categoryName;

            $result = $objProductModel->objUpdate('category', $data, $categoryID);

            if ($result['error'] == 0) {
                $response['error'] = 0;
                $response['msg'] = 'Categoría Actualizada';
            } else {
                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Ya existe la categoría';
        }

        return json_encode($response);
    } // OK

    public function changeProductStatus()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash'])) {
            $response['error'] = 2;
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response);
        }

        $objProductModel = new ProductModel;
        $productID = $this->request->getPost('productID');

        $data = array();
        $data['status'] = $this->request->getPost('status');

        $result = $objProductModel->objUpdate('product', $data, $productID);

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
    } // OK
}
