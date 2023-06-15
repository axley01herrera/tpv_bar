<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        return view('admin/index');
    }

    public function login()
    {
        $password = $this->request->getPost('password');
    }
}
