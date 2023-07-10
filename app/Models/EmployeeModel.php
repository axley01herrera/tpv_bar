<?php

namespace App\Models;

use CodeIgniter\CLI\Console;
use CodeIgniter\Model;
use CodeIgniter\Database\MySQLi\Builder;

class EmployeeModel extends Model
{
    protected $db;

    function  __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getActiveEmployees()
    {
        $query = $this->db->table('tpv_bar_employees')
        ->select('id,user')
        ->where('status', 1);

        return $query->get()->getResult();
    }

    public function verifyCredentials($user, $password)
    {
        $query = $this->db->table('tpv_bar_employees')
        ->where('id', $user);

        $result = $query->get()->getResult();
        $return = array();

        if(password_verify($password, $result[0]->clave)) { 

            if($result[0]->status == 1) {

                $return['error'] = 0;
                $return['data'] = $result[0];

            } else { // ERROR USER STATUS

                $return['error'] = 1;
                $return['code'] = 101;
                $return['msg'] = 'Usuario Desactivado';

            }
        } else { // ERROR INVALID PASSWORD
            
            $return['error'] = 1;
            $return['code'] = 100;
            $return['msg'] = 'Rectifique su contraseña';
        }

        return $return;
    }

    

    

    

    

    public function deleteUser($id)
    {
        $query = $this->db->table('employee')
            ->where('id', $id)
            ->delete();

        return $query->resultID;
    }

    

    

    

}