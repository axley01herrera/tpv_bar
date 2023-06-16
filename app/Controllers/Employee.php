<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class Employee extends BaseController
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
        $data['menu_ative'] = 'employee';
        $data['page'] = 'employee/index';

        return view('admin/layout/header', $data);
    } // OK

    public function showModalEmployee()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['action'] = $this->request->getPost('action');

        if ($data['action'] == 'create') {
            $data['title'] = 'Nuevo Empleado';
        } elseif ($data['action'] == 'update') {
            $objModel = new EmployeeModel;
            $result = $objModel->getUserData($this->request->getPost('userID'));
            $data['title'] = 'Actualizando ' . $result[0]->name . ' ' . $result[0]->lastName;
            $data['user_data'] = $result;
        }

        return view('admin/modals/employee', $data);
    } // OK

    public function processingEmployee()
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

        $objModel = new EmployeeModel;
        $result = $objModel->getUserProcessingData($params);
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
                $clave = '<button class="ms-1 me-1 btn btn-sm btn-primary btn-actions-clave" data-id="' . $result[$i]->id . '" data-action="set_clave"><span class="mdi mdi-key" title="Crear Clave"></span></button>';
            } else {
                $clave = '<button class="ms-1 me-1 btn btn-sm btn-primary btn-actions-clave" data-id="' . $result[$i]->id . '" data-action="update_clave"><span class="mdi mdi-key-minus" title="Cambiar Clave"></span></button>';
            }

            $btn_edit = '<button class="ms-1 me-1 btn btn-sm btn-warning btn-edit-employee" data-id="' . $result[$i]->id . '"><span class="mdi mdi-account-edit-outline" title="Editar Empleado"></span></button>';

            $col = array();
            $col['name'] = $result[$i]->name;
            $col['lastName'] = $result[$i]->lastName;
            $col['user'] = $result[$i]->user;
            $col['status'] = $status;
            $col['switch'] = $switch;
            $col['action'] = $clave . $btn_edit;

            $row[$i] =  $col;
        }

        if ($totalRows > 0)
            $totalRecords = $objModel->getTotalUser();

        $data = array();
        $data['draw'] = $dataTableRequest['draw'];
        $data['recordsTotal'] = intval($totalRecords);
        $data['recordsFiltered'] = intval($totalRecords);
        $data['data'] = $row;

        return json_encode($data);
    } // OK

    public function changeUserStatus()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['status'] = $this->request->getPost('status');

        $objModel = new EmployeeModel;
        $result = $objModel->updateUser($data, $this->request->getPost('userID'));

        if ($result['error'] == 0) {
            $msg = '';

            if ($data['status'] == 0)
                $msg = 'Usuario Desactivado';
            elseif ($data['status'] == 1)
                $msg = 'Usuario Activado';

            $response['error'] = 0;
            $response['msg'] = $msg;
        } else {

            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    } // OK

    public function showModalSetClave()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['userID'] = $this->request->getPost('userID');
        $data['action'] = $this->request->getPost('action');

        $objModel = new EmployeeModel;
        $userData = $objModel->getUserData($data['userID']);

        if ($data['action'] == 'set_clave')
            $data['title'] = 'Creando Contraseña para ' . $userData[0]->name . ' ' . $userData[0]->lastName;
        else
            $data['title'] = 'Actualizando Contraseña de ' . $userData[0]->name . ' ' . $userData[0]->lastName;

        return view('admin/modals/setClave', $data);
    } // OK

    public function setClave()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['clave'] = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT); // ENCRYPT PASSWORD

        $objModel = new EmployeeModel;
        $result = $objModel->updateUser($data, $this->request->getPost('userID'));

        if ($result['error'] == 0) {

            $response['error'] = 0;
            $response['msg'] = 'Proceso realizado con éxito';
        } else {

            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    } // OK

    public function createEmployee()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['name'] = trim($this->request->getPost('name'));
        $data['lastName'] = trim($this->request->getPost('lastName'));
        $data['user'] = trim($this->request->getPost('user'));

        $objModel = new EmployeeModel;
        $resultCheckUserExist = $objModel->checkUserExist($data['user']);

        if (empty($resultCheckUserExist)) {

            $result = $objModel->createUser($data);

            if ($result['error'] == 0) {

                $response['error'] = 0;
                $response['msg'] = 'Empleado Creado';
            } else {

                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error';
            }
        } else {

            $response['error'] = 3;
            $response['msg'] = 'Ya existe un empleado con el usuario introducido';
        }

        return json_encode($response);
    } // OK

    public function updateEmployee()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $user = $this->request->getPost('user');
        $id = $this->request->getPost('userID');

        $objModel = new EmployeeModel;
        $result_checkUserExist = $objModel->checkUserExist($user, $id);

        if (empty($result_checkUserExist)) {

            $data = array();
            $data['user'] = $user;
            $data['name'] = $this->request->getPost('name');
            $data['lastName'] = $this->request->getPost('lastName');

            $result_update = $objModel->updateUser($data, $id);

            if ($result_update['error'] == 0) {

                $response['error'] = 0;
                $response['msg'] = 'Empleado Actualizado';
            } else {

                $response['error'] = 1;
                $response['msg'] = 'Ha ocurrido un error en el proceso';
            }
        } else {

            $response['error'] = 3;
            $response['msg'] = 'Ya existe un empleado con el usuario introducido';
        }

        return json_encode($response);
    } // OK
}
