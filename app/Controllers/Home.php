<?php

namespace App\Controllers;

class Home extends BaseController{

    public $objRequestService;
    
    public function index()
    {
        
        return view('admin/login');
    }
}
