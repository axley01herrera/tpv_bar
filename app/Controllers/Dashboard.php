<?php

namespace App\Controllers;

class Dashboard extends BaseController
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
        $data['menu_ative'] = 'dashboard';
        $data['page'] = 'admin/dashboard/index';

        return view('admin/layout/header', $data);
    }
}
