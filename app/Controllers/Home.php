<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\AdminModel;

class Home extends BaseController
{
    protected $objSession;
    public $objEmployeeModel;
    public $objAdminModel;

    function  __construct()
    {
        $this->objSession = session();
        $this->objEmployeeModel = new EmployeeModel;
        $this->objAdminModel = new AdminModel;

        # DESTROY SESSION
        $sessionArray['id'] = '';
        $sessionArray['name'] = '';
        $sessionArray['lastName'] = '';
        $sessionArray['user'] = '';

        $this->objSession->set('user', $sessionArray);
    }

    public function index()
    {
        $data = array();
        $data['employees'] = $this->objEmployeeModel->getActiveEmployees();
        $data['countEmployees'] = sizeof($data['employees']);
        $data['msg'] = $this->request->getPostGet('msg');

        return view('authentication/loginEmployees', $data);
    }

    public function login()
    {
        $user = $this->request->getGetPost('user');
        $password = $this->request->getGetPost('password');

        $objEmployeeModel = new EmployeeModel;
        $result = $objEmployeeModel->verifyCredentials($user, $password);

        if ($result['error'] == 0) {

            # CREATE SESSION
            $sessionArray = array();
            $sessionArray['id'] = $result['data']->id;
            $sessionArray['name'] = $result['data']->name;
            $sessionArray['lastName'] = $result['data']->lastName;
            $sessionArray['user'] = $result['data']->user;

            $this->objSession->set('user', $sessionArray);
        }

        return json_encode($result);
    }

    public function adminAuthentication()
    {
        $data = array();
        $data['msg'] = $this->request->getPostGet('msg');

        return view('authentication/loginAdministrator', $data);
    }

    public function loginAdmin()
    {
        $response = array();

        if ($this->objAdminModel->verifyCredentials($this->request->getPost('password')) === true) {

            $response['error'] = 0;
            
            # CREATE SESSION
            $sessionArray = array();
            $sessionArray['id'] = md5(uniqid());

            $this->objSession->set('user', $sessionArray);

        } else { // ERROR AUTH

            $response['error'] = 1;
            $response['msg'] = 'Rectifique su contraseña';

        }

        return json_encode($response);
    }
}
