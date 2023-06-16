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
        $query = $this->db->table('product');

        if (!empty($params['search'])) {
            $query->like('name', $params['search']);
            $query->orLike('cat', $params['search']);
            $query->orLike('price', $params['search']);
            $query->orLike('description', $params['search']);
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
                $sort = 'name ASC';
            else
                $sort = 'name DESC';
        }

        if ($column == 1) {
            if ($dir == 'asc')
                $sort = 'cat ASC';
            else
                $sort = 'cat DESC';
        }

        if ($column == 2) {
            if ($dir == 'asc')
                $sort = 'price ASC';
            else
                $sort = 'price DESC';
        }

        if ($column == 3) {
            if ($dir == 'asc')
                $sort = 'description ASC';
            else
                $sort = 'description DESC';
        }

        return $sort;
    }

    public function updateProduct($data, $id)
    {
        $return = array();

        $query = $this->db->table('product')
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

    public function deleteProduct($id)
    {
        $query = $this->db->table('product')
            ->where('id', $id)
            ->delete();

        return $query->resultID;
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
        $query = $this->db->table('product')
            ->selectCount('id')
            ->get()->getResult();

        return $query[0]->id;
    }


}