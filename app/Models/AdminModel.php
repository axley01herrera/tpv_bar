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

    public function objCreate($table, $data)
    {
        $return = array();

        $query = $this->db->table($table)
            ->insert($data);

        if ($query->resultID == true) {

            $return['error'] = 0;
            $return['id'] = $query->connID->insert_id;

        } else
            $return['error'] = 1;

        return $return;
    }

    public function objUpdate($table, $data, $id)
    {
        $return = array();

        $query = $this->db->table($table)
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

    public function verifyCredentials($password)
    {
        $query = $this->db->table('tpv_bar_administrator');
        $data = $query->get()->getResult();

        if (password_verify($password, $data[0]->password))
            return true;
        else
            return false;
    }

    public function getEmployeesProcessingData($params)
    {
        $query = $this->db->table('tpv_bar_employees');

        if (!empty($params['search'])) {
            $query->like('name', $params['search']);
            $query->orLike('lastName', $params['search']);
            $query->orLike('user', $params['search']);
        }

        $query->offset($params['start']);
        $query->limit($params['length']);
        $query->orderBy($this->getEmployeesProcessingSort($params['sortColumn'], $params['sortDir']));

        return $query->get()->getResult();
    }

    public function getEmployeesProcessingSort($column, $dir)
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

    public function getTotalEmployees()
    {
        $query = $this->db->table('tpv_bar_employees')
            ->selectCount('id')
            ->get()->getResult();

        return $query[0]->id;
    }

    public function getEmployeeData($id)
    {
        $query = $this->db->table('tpv_bar_employees')
            ->where('id', $id);

        return $query->get()->getResult();
    }

    public function checkUserExist($user, $id = '')
    {
        $query = $this->db->table('tpv_bar_employees')
            ->where('user', $user);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }
}
