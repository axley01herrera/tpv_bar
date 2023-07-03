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

    public function verifyCredentials($user, $password)
    {
        $return = array();

        $query = $this->db->table('employee')
        ->where('id', $user);

        $result = $query->get()->getResult();

        if($result[0]->status == 1) { // VALIDATE STATUS
            if(password_verify($password, $result[0]->clave)) { // VERIFY PASSWORD
                $return['error'] = 0;
                $return['data'] = $result[0];
            } else {
                $return['error'] = 1;
                $return['msg'] = 'Rectifique su contraseña';
            }
        } else {
            $return['error'] = 1;
            $return['msg'] = 'Usuario Desactivado';
        }

        return $return;
    }

    public function getUserData($id)
    {
        $query = $this->db->table('employee')
            ->where('id', $id);

        return $query->get()->getResult();
    }

    public function getUserProcessingData($params)
    {
        $query = $this->db->table('employee');

        if (!empty($params['search'])) {
            $query->like('name', $params['search']);
            $query->orLike('lastName', $params['search']);
            $query->orLike('user', $params['search']);
        }

        $query->offset($params['start']);
        $query->limit($params['length']);
        $query->orderBy($this->getUserProcessingSort($params['sortColumn'], $params['sortDir']));

        return $query->get()->getResult();
    }

    public function getUserProcessingSort($column, $dir)
    {
        $sort = '';

        if ($column == 0) {
            if ($dir == 'asc')
                $sort = 'name ASC';
            else
                $sort = 'name DESC';
        }

        if ($column == 1) {
            if ($dir == 'asc')
                $sort = 'lastName ASC';
            else
                $sort = 'lastName DESC';
        }

        if ($column == 2) {
            if ($dir == 'asc')
                $sort = 'user ASC';
            else
                $sort = 'user DESC';
        }

        if ($column == 3) {
            if ($dir == 'asc')
                $sort = 'status ASC';
            else
                $sort = 'status DESC';
        }

        return $sort;
    }

    public function createUser($data)
    {
        $return = array();

        $query = $this->db->table('employee')
            ->insert($data);

        if ($query->resultID == true) {
            $return['error'] = 0;
            $return['id'] = $query->connID->insert_id;
        } else
            $return['error'] = 1;

        return $return;
    }

    public function updateUser($data, $id)
    {
        $return = array();

        $query = $this->db->table('employee')
            ->where('id', $id)
            ->update($data);

        if ($query == true) {
            $return['error'] = 0;
            $return['id'] = $id;
        } else {
            $return['error'] = 1;
            $return['id'] = $id;
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

    public function checkUserExist($user, $id = '')
    {
        $query = $this->db->table('employee')
            ->where('user', $user);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }

    public function getTotalUser()
    {
        $query = $this->db->table('employee')
            ->selectCount('id')
            ->get()->getResult();

        return $query[0]->id;
    }

    public function getLoginEmployees()
    {
        $query = $this->db->table('employee')
        ->select('id,user')
        ->where('status', 1);

        return $query->get()->getResult();
    }

}