<?php

namespace App\Models;

use CodeIgniter\CLI\Console;
use CodeIgniter\Model;
use CodeIgniter\Database\MySQLi\Builder;

class ProductModel extends Model
{
    protected $db;

    function  __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function objCreate($table, $data)
    {
        $query = $this->db->table($table)
        ->insert($data);

        if ($query->resultID == true) {
            $return['error'] = 0;
            $return['id'] = $query->connID->insert_id;
        } else
            $return['error'] = 1;

        return $return;
    }

    public function getProductData($id)
    {
        $query = $this->db->table('product')
            ->where('id', $id);

        return $query->get()->getResult();
    }

    public function getProductProcessingData($params)
    {
        $query = $this->db->table('product_view');

        if (!empty($params['search'])) {
            $query->like('productName', $params['search']);
            $query->orLike('categoryName', $params['search']);
            $query->orLike('productPrice', $params['search']);
            $query->orLike('productStatus', $params['search']);
        }

        $query->offset($params['start']);
        $query->limit($params['length']);
        $query->orderBy($this->getProductProcessingSort($params['sortColumn'], $params['sortDir']));

        return $query->get()->getResult();
    }

    public function getProductProcessingSort($column, $dir)
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

    public function checkProductExist($name, $id = '')
    {
        $query = $this->db->table('product')
            ->where('name', $name);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }

    public function getTotalProduct()
    {
        $query = $this->db->table('product_view')
            ->selectCount('productID')
            ->get()->getResult();

        return $query[0]->productID;
    }

    public function getCategories()
    {
        $query = $this->db->table('category');
        return $query->get()->getResult();
    }

    public function checkCattExist($name, $id = '')
    {
        $query = $this->db->table('category')
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
        $query = $this->db->table('category')
        ->where('id', $id);

        return $query->get()->getResult();
    }

}