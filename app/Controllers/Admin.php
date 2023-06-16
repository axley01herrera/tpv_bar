<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Admin extends BaseController
{
    protected $objSession;

    function  __construct()
    {
        $this->objSession = session();

        # DESTROY SESSION
        $sessionArray = array();
        $sessionArray['hash'] = '';

        $this->objSession->set('user', $sessionArray);
    }

    public function index()
    {
        $data = array();
        $data['msg'] = $this->request->getPostGet('msg');

        return view('admin/login', $data);
    }

    public function login()
    {
        $response = array();
        $response['error'] = '';
        $response['msg'] = '';

        $objAdminModel = new AdminModel;

        $password =  $this->request->getPost('password');

        if ($objAdminModel->verifyCredentials($password) == true) {
            $response['error'] = 0;
            $response['msg'] = 'Autenticado';

            # CREATE SESSION
            $sessionArray = array();
            $sessionArray['hash'] = md5($password);

            $this->objSession->set('user', $sessionArray);
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Rectifique su contraseña';
        }

        return json_encode($response);
    }
}
