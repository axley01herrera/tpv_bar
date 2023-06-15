<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $db;

    function  __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function verifyCredentials($password)
    {
        $query = $this->db->table('user_admin');
        $data = $query->get()->getResult();

        if(password_verify($password, $data[0]->password)) 
            return true;
        else
            return false;
    }
}