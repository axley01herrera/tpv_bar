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

    # OBJ CREATE | UPDATE
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

    # AUTHENTICATION 

    public function verifyCredentials($password)
    {
        $query = $this->db->table('tpv_bar_administrator');
        $data = $query->get()->getResult();

        if (password_verify($password, $data[0]->password)) 
            return true;
        else
            return false;
    }

    # EMPLOYEE

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

    public function getAdminData($id)
    {
        $query = $this->db->table('tpv_bar_administrator')
            ->where('id', $id);

        return $query->get()->getResult();
    }

    public function objData($table)
    {
        $query = $this->db->table($table)
            ->select('*');

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

    # PRODUCTS

    public function getProductsProcessingData($params)
    {
        $query = $this->db->table('tpv_bar_product_view');

        if (!empty($params['search'])) {
            $query->like('productName', $params['search']);
            $query->orLike('categoryName', $params['search']);
            $query->orLike('productPrice', $params['search']);
            $query->orLike('productStatus', $params['search']);
        }

        $query->offset($params['start']);
        $query->limit($params['length']);
        $query->orderBy($this->getProductsProcessingSort($params['sortColumn'], $params['sortDir']));

        return $query->get()->getResult();
    }

    public function getProductsProcessingSort($column, $dir)
    {
        $sort = '';

        if ($column == 0) {
            if ($dir == 'asc')
                $sort = 'productName ASC';
            else
                $sort = 'productName DESC';
        }

        if ($column == 1) {
            if ($dir == 'asc')
                $sort = 'categoryName ASC';
            else
                $sort = 'categoryName DESC';
        }

        if ($column == 2) {
            if ($dir == 'asc')
                $sort = 'productPrice ASC';
            else
                $sort = 'productPrice DESC';
        }

        if ($column == 3) {
            if ($dir == 'asc')
                $sort = 'productStatus ASC';
            else
                $sort = 'productStatus DESC';
        }

        return $sort;
    }

    public function getTotalProducts()
    {
        $query = $this->db->table('tpv_bar_product_view')
            ->selectCount('productID')
            ->get()->getResult();

        return $query[0]->productID;
    }

    public function getProductData($id = null, $fk_category = null)
    {
        $query = $this->db->table('tpv_bar_product');

        if (!empty($id))
            $query->where('id', $id);

        if (!empty($fk_category)) {
            $query->where('fk_category', $fk_category);
            $query->where('status', 1);
        }

        return $query->get()->getResult();
    }

    public function checkProductExist($name, $id = '')
    {
        $query = $this->db->table('tpv_bar_product')
            ->where('name', $name);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }

    public function getActiveProducts()
    {
        $query = $this->db->table('tpv_bar_product')
            ->where('status', 1);

        return $query->get()->getResult();
    }

    # CATEGORY

    public function getCategories()
    {
        $query = $this->db->table('tpv_bar_category')
            ->orderBy('name', 'asc');

        return $query->get()->getResult();
    }

    public function checkCattExist($name, $id = '')
    {
        $query = $this->db->table('tpv_bar_category')
            ->where('name', $name);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }

    public function getCatData($id)
    {
        $query = $this->db->table('tpv_bar_category')
            ->where('id', $id);

        return $query->get()->getResult();
    }
}
