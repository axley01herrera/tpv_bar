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
            $query->orLike('email', $params['search']);
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
                $sort = 'email ASC';
            else
                $sort = 'email DESC';
        }

        if ($column == 3) {
            if ($dir == 'asc')
                $sort = 'status ASC';
            else
                $sort = 'status DESC';
        }

        return $sort;
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

}