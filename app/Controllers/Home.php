<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class Home extends BaseController
{
    protected $objSession;

    function  __construct()
    {
        $this->objSession = session();

        # DESTROY SESSION
        $sessionArray['id'] = '';
        $sessionArray['name'] = '';
        $sessionArray['lastName'] = '';
        $sessionArray['user'] = '';

        $this->objSession->set('user', $sessionArray);
    }

    public function index()
    {
        $objEmployeeModel = new EmployeeModel;
        $result = $objEmployeeModel->getLoginEmployees();

        $data = array();
        $data['users'] = $result;
        $data['countUsers'] = sizeof($data['users']);
        $data['msg'] = $this->request->getPostGet('msg');

        return view('login/index', $data);
    }

    public function login()
    {
        $response = array();
        $response['error'] = '';
        $response['msg'] = '';

        $user = $this->request->getGetPost('user');
        $pass = $this->request->getGetPost('pass');

        $objEmployeeModel = new EmployeeModel;

        $result = $objEmployeeModel->verifyCredentials($user, $pass);

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
}
