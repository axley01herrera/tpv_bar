<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Administrator extends BaseController
{
    protected $objSession;
    public $objAdminModel;

    function  __construct()
    {
        $this->objSession = session();
        $this->objAdminModel = new AdminModel;
    }

    public function index()
    {
        return view('logout');
    }

    public function dashboard()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
            return view('logout');

        $data = array();
        $data['menu_ative'] = 'dashboard';
        $data['page'] = 'admin/dashboard/index';

        return view('admin/header', $data);
    }

    public function employees()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id']))
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
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $data = array();
        $data['name'] = htmlspecialchars(trim($this->request->getPost('name')));
        $data['lastName'] = htmlspecialchars(trim($this->request->getPost('lastName')));
        $data['user'] = htmlspecialchars(trim($this->request->getPost('user')));

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
            $response['msg'] = 'Sesión Expirada';

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
            $response['msg'] = 'Sesión Expirada';

            return json_encode($response); // ERROR SESSION EXPIRED
        }

        $id = $this->request->getPost('id');

        $data = array();
        $data['status'] = (int) $this->request->getPost('status');

        $result = $this->objAdminModel->objUpdate('tpv_bar_employees', $data, $id);

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
            $data['title'] = 'Actualizando Contraseña de ' . $userData[0]->name . ' ' . $userData[0]->lastName;

        return view('admin/modals/setClave', $data);
    }

    public function setClave()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')) || empty($this->objSession->get('user')['id'])) {

            $response['error'] = 1;
            $response['code'] = 103;
            $response['msg'] = 'Sesión Expirada';

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
}
