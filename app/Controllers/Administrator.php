<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Administrator extends BaseController
{
    protected $objSession;
    public $objAdminModel;
    public $config;

    function  __construct()
    {
        $this->objSession = session();
        $this->objAdminModel = new AdminModel;

        $this->config = $this->objAdminModel->getConfigData();
    }

    public function dashboard()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $data = array();
        $data['menu_ative'] = 'dashboard';
        $data['page'] = 'admin/dashboard/index';
        $data['config'] = $this->config;;

        return view('admin/header', $data);
    }

    # EMPLOYEES

    public function employees()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $data = array();
        $data['menu_ative'] = 'employee';
        $data['page'] = 'admin/employee/listEmployee';

        return view('admin/header', $data);
    }

    public function dtProcessingEmployees()
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

        $result = $this->objAdminModel->getEmployeesProcessingData($params);
        $totalRows = sizeof($result);

        for ($i = 0; $i < $totalRows; $i++) {

            $status = '';
            $switch = '';

            if ($result[$i]->status == 1) {
                $status = '<span class="badge bg-success">Activo</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2" title="desactivar/activar" >
                                                <input data-id="' . $result[$i]->id . '" data-status="' . $result[$i]->status . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" checked />
                                            </div>';
            } else {
                $status = '<span class="badge bg-danger">Inactivo</span>';
                $switch = '<div style="margin-left: 44px;" class="form-check form-switch form-switch-md mb-2" title="desactivar/activar">
                                                <input data-id="' . $result[$i]->id . '" data-status="' . $result[$i]->status . '" class="form-check-input switch" type="checkbox" id="flexSwitchCheckChecked" />
                                            </div>';
            }

            $clave = '';

            if (empty($result[$i]->clave)) {
                $clave = '<button class="btn btn-sm btn-primary btn-actions-clave" data-id="' . $result[$i]->id . '" data-action="set_clave"><span class="mdi mdi-account-key-outline" title="Crear Clave"></span></button>';
            } else {
                $clave = '<button class="btn btn-sm btn-primary btn-actions-clave" data-id="' . $result[$i]->id . '" data-action="update_clave"><span class="mdi mdi-account-key-outline" title="Cambiar Clave"></span></button>';
            }

            $btn_edit = '<button class="btn btn-sm btn-warning btn-edit-employee" data-id="' . $result[$i]->id . '"><span class="mdi mdi-account-edit-outline" title="Editar Empleado"></span></button>';

            $col = array();
            $col['name'] = $result[$i]->name;
            $col['lastName'] = $result[$i]->lastName;
            $col['user'] = $result[$i]->user;
            $col['status'] = $status;
            $col['switch'] = $switch;
            $col['action'] = $clave.' '.$btn_edit;

            $row[$i] =  $col;
        }

        if ($totalRows > 0) {

            if (empty($params['search']))
                $totalRecords = $this->objAdminModel->getTotalEmployees();
            else
                $totalRecords = $totalRows;
        }

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    }

    public function showModalEmployee()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['action'] = $this->request->getPost('action');

        if ($data['action'] == 'create')
            $data['title'] = 'Nuevo Empleado';
        elseif ($data['action'] == 'update') {

            $result = $this->objAdminModel->getEmployeeData($this->request->getPost('userID'));
            $data['title'] = 'Actualizando ' . $result[0]->name . ' ' . $result[0]->lastName;
            $data['userData'] = $result;
        }

        return view('admin/modals/employee', $data);
    }

    public function createEmployee()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;
    
            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $data = array();
        $data['name'] = htmlspecialchars(trim($this->request->getPost('name')));
        $data['lastName'] = htmlspecialchars(trim($this->request->getPost('lastName')));
        $data['user'] = htmlspecialchars(trim($this->request->getPost('user')));
        $data['clave'] = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT); // ENCRYPT PASSWORD

        $resultCheckUserExist = $this->objAdminModel->checkUserExist($data['user']);

        if (empty($resultCheckUserExist)) {

            $result = $this->objAdminModel->objCreate('tpv_bar_employees', $data);

            if ($result['error'] == 0) { // SUCCESS

                $response['error'] = 0;
                $response['id'] = $result['id'];
                $response['msg'] = 'Empleado Creado';
            } else { // ERROR CREATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else { // ERRROR DUPLICATE RECORD

            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe un empleado con el usuario introducido';
        }

        return json_encode($response);
    }

    public function updateEmployee()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $user = htmlspecialchars(trim($this->request->getPost('user')));
        $id = $this->request->getPost('userID');

        $resultCheckUserExist = $this->objAdminModel->checkUserExist($user, $id);

        if (empty($resultCheckUserExist)) {

            $data = array();
            $data['user'] = $user;
            $data['name'] = htmlspecialchars(trim($this->request->getPost('name')));
            $data['lastName'] = htmlspecialchars(trim($this->request->getPost('lastName')));

            $result = $this->objAdminModel->objUpdate('tpv_bar_employees', $data, $id);

            if ($result['error'] == 0) { // SUCCESS

                $response['error'] = 0;
                $response['id'] = $id;
                $response['msg'] = 'Empleado Actualizado';
            } else { // ERROR UPDATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else { // ERRROR DUPLICATE RECORD

            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe un empleado con el usuario introducido';
        }

        return json_encode($response);
    }

    public function changeEmployeeStatus()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('id');

        $data = array();
        $data['status'] = (int) $this->request->getPost('status');

        $result = $this->objAdminModel->objUpdate('tpv_bar_employees', $data, $id);

        if ($result['error'] == 0) { // SUCCESS

            $msg = '';

            if ($data['status'] == 0)
                $msg = 'Usuario Desactivado';
            elseif ($data['status'] == 1)
                $msg = 'Usuario Activado';

            $response['error'] = 0;
            $response['msg'] = $msg;
        } else { // ERROR UPDATE RECORD

            $response['error'] = 1;
            $response['code'] = 100;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    public function showModalSetClave()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['id'] = $this->request->getPost('id');
        $data['action'] = $this->request->getPost('action');

        $userData = $this->objAdminModel->getEmployeeData($this->request->getPost('id'));

        if ($data['action'] == 'set_clave')
            $data['title'] = 'Creando Contraseña para ' . $userData[0]->name . ' ' . $userData[0]->lastName;
        else
            $data['title'] = 'Actualizando Clave de ' . $userData[0]->name . ' ' . $userData[0]->lastName;

        return view('admin/modals/setClave', $data);
    }

    public function setClave()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('id');

        $data = array();
        $data['clave'] = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT); // ENCRYPT PASSWORD

        $result = $this->objAdminModel->objUpdate('tpv_bar_employees', $data, $id);

        if ($result['error'] == 0) { // SUCCESS

            $response['error'] = 0;
            $response['id'] = $id;
            $response['msg'] = 'Empleado Actualizado';
        } else { // ERROR UPDATE RECORD

            $response['error'] = 1;
            $response['code'] = 100;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    # PRODUCTS

    public function products()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $data = array();
        $data['menu_ative'] = 'product';
        $data['page'] = 'admin/product/listProduct';

        return view('admin/header', $data);
    }

    public function dtProcessingProducts()
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

        $result = $this->objAdminModel->getProductsProcessingData($params);

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

            if (empty($params['search']))
                $totalRecords = $this->objAdminModel->getTotalProducts();
            else
                $totalRecords = $totalRows;
        }

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    }

    public function showModalProduct()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['action'] = $this->request->getPost('action');
        $data['categories'] = $this->objAdminModel->getCategories();
        $data['countCategories'] = sizeof($data['categories']);

        if ($data['action'] == 'create')
            $data['title'] = 'Nuevo Producto';
        elseif ($data['action'] == 'update') {
            $data['product'] = $this->objAdminModel->getProductData($this->request->getPost('productID'));
            $data['title'] = 'Actualizando ' . $data['product'][0]->name;
        }

        return view('admin/modals/product', $data);
    }

    public function createProduct()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $data = array();
        $data['name'] = htmlspecialchars(trim($this->request->getPost('productName')));
        $data['price'] = htmlspecialchars(trim($this->request->getPost('productPrice')));
        $data['description'] = htmlspecialchars(trim($this->request->getPost('productDescription')));
        $data['fk_category'] = $this->request->getPost('categoryID');

        $resultCheckProductExist = $this->objAdminModel->checkProductExist($data['name']);

        if (empty($resultCheckProductExist)) {

            $result = $this->objAdminModel->objCreate('tpv_bar_product', $data);

            if ($result['error'] == 0) { // SUCCESS

                $response['error'] = 0;
                $response['id'] = $result['id'];
                $response['msg'] = 'Producto Creado';
            } else { // ERROR CREATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else { // ERROR DUPLICATE RECORD

            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe el producto';
        }

        return json_encode($response);
    }

    public function updateProduct()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $name = htmlspecialchars(trim($this->request->getPost('productName')));
        $id = $this->request->getPost('productID');

        $resultCheckProductExist = $this->objAdminModel->checkProductExist($name, $id);

        if (empty($resultCheckProductExist)) {

            $data = array();
            $data['name'] = $name;
            $data['price'] = trim($this->request->getPost('productPrice'));
            $data['description'] = trim($this->request->getPost('productDescription'));
            $data['fk_category'] = $this->request->getPost('categoryID');

            $result = $this->objAdminModel->objUpdate('tpv_bar_product', $data, $id);

            if ($result['error'] == 0) {

                $response['error'] = 0;
                $response['id'] = $id;
                $response['msg'] = 'Producto Actualizado';
            } else { // ERROR UPDATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else { // ERROR DUPLICATE RECORD

            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe el producto';
        }

        return json_encode($response);
    }

    public function changeProductStatus()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('productID');

        $data = array();
        $data['status'] = $this->request->getPost('status');

        $result = $this->objAdminModel->objUpdate('tpv_bar_product', $data, $id);

        if ($result['error'] == 0) { // SUCCESS

            $msg = '';

            if ($data['status'] == 0)
                $msg = 'Producto Desactivado';
            elseif ($data['status'] == 1)
                $msg = 'Producto Activado';

            $response['error'] = 0;
            $response['msg'] = $msg;
        } else { // ERROR UPDATE RECORD

            $response['error'] = 1;
            $response['code'] = 100;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    # CATEGORIES

    public function catDataTable()
    {
        $result = $this->objAdminModel->getCategories();
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
    }

    public function showModalCat()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['action'] = $this->request->getPost('action');

        if ($data['action'] == 'create') // CREATE
            $data['title'] = 'Nueva Categoría de Producto';
        elseif ($data['action'] == 'update') {
            $data['category'] = $this->objAdminModel->getCatData($this->request->getPost('categoryID'));
            $data['title'] = 'Editando Categoría ' . $data['category'][0]->name;
        }

        return view('admin/modals/cat', $data);
    }

    public function createCat()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $categoryName = htmlspecialchars(trim($this->request->getPost('categoryName')));
        $resultCheckCatExist = $this->objAdminModel->checkCattExist($categoryName);

        if (empty($resultCheckCatExist)) {

            $data = array();
            $data['name'] = $categoryName;

            $result = $this->objAdminModel->objCreate('tpv_bar_category', $data);

            if ($result['error'] == 0) { // SUCCESS

                $response['error'] = 0;
                $response['id'] = $result['id'];
                $response['msg'] = 'Categoría Creada';
            } else { // ERROR CREATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else { // ERROR DUPLICATE RECORD

            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe la categoría';
        }

        return json_encode($response);
    }

    public function updateCat()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('categoryID');
        $categoryName = htmlspecialchars(trim($this->request->getPost('categoryName')));
        $resultCheckCatExist = $this->objAdminModel->checkCattExist($categoryName, $id);

        if (empty($resultCheckCatExist)) {

            $data = array();
            $data['name'] = $categoryName;

            $result = $this->objAdminModel->objUpdate('tpv_bar_category', $data, $id);

            if ($result['error'] == 0) { // SUCCESS

                $response['error'] = 0;
                $response['id'] = $id;
                $response['msg'] = 'Categoría Actualizada';
            } else { // ERROR UPDATE RECORD

                $response['error'] = 1;
                $response['code'] = 100;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else { // ERROR DUPLICATE RECORD
            $response['error'] = 1;
            $response['code'] = 104;
            $response['msg'] = 'Ya existe la categoría';
        }

        return json_encode($response);
    }

    # TOP BAR OPTIONS

    public function showModalConfig()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data['title'] = 'Configuración';
        $data['config'] = $this->config;

        return view('admin/modals/config', $data);
    }

    public function setConfig()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('id');

        $data = array();
        $data['name'] = htmlspecialchars(trim(strtoupper($this->request->getPost('name'))));
        $data['cif'] = htmlspecialchars(trim($this->request->getPost('cif')));
        $data['intTables'] = $this->request->getPost('intTables');
        $data['outTables'] = $this->request->getPost('outTables');

        $this->objAdminModel->objUpdate('tpv_bar_configuration', $data, $id);

        $response['error'] = 0;
        $response['msg'] = 'Configuracón Actualizada';

        return json_encode($response);
    }

    public function showModalChangeKey()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['title'] = 'Contraseña de Administrador';

        return view('admin/modals/changeKey', $data);
    }

    public function updateKey()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = array();
        $data['password'] = $password;

        $result = $this->objAdminModel->objUpdate('tpv_bar_administrator', $data, 1);

        if ($result['error'] == 0) { // SUCCESS

            $response['error'] = 0;
            $response['id'] = 1;
            $response['msg'] = 'Contraseña Actualizada';
        } else { // ERROR UPDATE RECORD

            $response['error'] = 1;
            $response['code'] = 100;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    }

    # DASHBOARD

    public function getCollectionDay()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $result = $this->objAdminModel->getCollectionDay();

        $data = array();
        $data['collectionDay'] = $result;

        return view('admin/dashboard/collectionDay', $data);
    }

    public function getChartWeek()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $result = $this->objAdminModel->getChartWeek();

        $data = array();
        $data['chartWeek'] = $result;

        return view('admin/dashboard/chartWeek', $data);
    }

    public function getChartMont()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        if (!empty($this->request->getPostGet('year')))
            $year = $this->request->getPostGet('year');
        else
            $year = date('Y');

        $result = $this->objAdminModel->getChartMont($year);

        $data = array();
        $data['chartMont'] = $result;
        $data['year'] = $year;
        $data['currentYear'] = date('Y');

        return view('admin/dashboard/chartMont', $data);
    }

    public function dtProcessingHistory()
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

        $result = $this->objAdminModel->getHistoryProcessingData($params);
        $totalRows = sizeof($result);

        for ($i = 0; $i < $totalRows; $i++) {

            if ($result[$i]->payType == 1)
                $spanPayType = '<small class="badge badge-soft-success font-13 ms-2"><img src="http://tpv/assets/images/dcash.png" alt="cash" width="25px"></small>';
            else
                $spanPayType = '<small class="badge badge-soft-success font-13 ms-2"><img src="http://tpv/assets/images/dcreditcard.png" alt="credit card" width="25px"></small>';

            $btnOpen = '<button type="button" class="btn-open btn btn-sm btn-success" data-id="' . $result[$i]->tableID . '" title="Re-abrir"><i class="mdi mdi-lock-open-variant-outline"></i></button>';
            $btnCancel = '<button type="button" class="btn-cancel btn btn-sm btn-danger" data-id="' . $result[$i]->tableID . '" title="Cancelar mesa"><i class="mdi mdi-block-helper"></i></button>';
            $btnPrint = '<button type="button" class="btn-print btn btn-sm btn-secondary" data-id="' . $result[$i]->tableID . '" title="Imprimir Ticket"><i class="mdi mdi-printer"></i></button>';

            $col = array();
            $col['tableID'] = $result[$i]->tableID;
            $col['tableName'] = '<a target="blank" href="'. base_url('TPV/ticketDetail') .'/'. $result[$i]->tableID .'">'. $result[$i]->tableName.'</a>';
            $col['dateOpen'] = $result[$i]->dateOpen;
            $col['dateClose'] = $result[$i]->dateClose;
            $col['employee'] = $result[$i]->employeeName . ' ' . $result[$i]->employeeLastName;
            $col['payType'] = @$spanPayType . $result[$i]->payTypeLabel;
            $col['amount'] = '€ ' . number_format((float) $result[$i]->amount, 2, ".", ',');
            $col['actions'] = $btnOpen . ' ' . $btnCancel . ' ' . $btnPrint;

            $row[$i] =  $col;
        }

        if ($totalRows > 0) {

            if (empty($params['search']))
                $totalRecords = $this->objAdminModel->getTotalHistory();
            else
                $totalRecords = $totalRows;
        }

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    }

    public function dtProcessingHistoryCancel()
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

        $result = $this->objAdminModel->getHistoryCancelProcessingData($params);
        $totalRows = sizeof($result);

        for ($i = 0; $i < $totalRows; $i++) {

            if ($result[$i]->payType == 1)
                $spanPayType = '<small class="badge badge-soft-success font-13 ms-2"><img src="http://tpv/assets/images/dcash.png" alt="cash" width="25px"></small>';
            else
                $spanPayType = '<small class="badge badge-soft-success font-13 ms-2"><img src="http://tpv/assets/images/dcreditcard.png" alt="credit card" width="25px"></small>';


            $col = array();
            $col['tableID'] = $result[$i]->tableID;
            $col['status'] = '<span class="badge badge-soft-danger">Cancelada</span>';
            $col['employee'] = $result[$i]->employeeName . ' ' . $result[$i]->employeeLastName;
            $col['tableName'] = $result[$i]->tableName;
            $col['dateOpen'] = $result[$i]->dateOpen;
            $col['dateClose'] = $result[$i]->dateClose;
            $col['payType'] = @$spanPayType . $result[$i]->payTypeLabel;
            $col['amount'] = '€ ' . number_format((float) $result[$i]->amount, 2, ".", ',');

            $row[$i] =  $col;
        }

        if ($totalRows > 0) {

            if (empty($params['search']))
                $totalRecords = $this->objAdminModel->getTotalHistory();
            else
                $totalRecords = $totalRows;
        }

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    }

    # REPORT
    public function report() 
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $data = array();
        $data['menu_ative'] = 'report';
        $data['page'] = 'admin/report/mainReport';

        return view('admin/header', $data);
    }

    public function returnContentReport()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $data = array();
        $data['dateStart'] = $this->request->getPost('dateStart');
        $data['dateEnd'] = $this->request->getPost('dateEnd');

        return view('admin/report/content', $data);
    }

    public function getCollectionReport()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')))
            return view('logout');
        elseif (empty($this->objSession->get('user')['role']))
            return view('logout');

        $dateStart = $this->request->getPost('dateStart');
        $dateEnd = $this->request->getPost('dateEnd');

        $result = $this->objAdminModel->getCollection($dateStart, $dateEnd);

        $data = array();
        $data['collectionDay'] = $result;
        $data['dateStart'] = $dateStart;
        $data['dateEnd'] = $dateEnd;

        return view('admin/report/collection', $data);
    }

    public function printReport()
    {
        $dateStart = $this->request->getPostGet('dateStart');
        $dateEnd = $this->request->getPostGet('dateEnd');

        $result = $this->objAdminModel->getCollection($dateStart, $dateEnd);

        $data = array();
        $data['collectionDay'] = $result;
        $data['dateStart'] = $dateStart;
        $data['dateEnd'] = $dateEnd;

        return view('admin/report/printReport', $data);
    }
    
}
