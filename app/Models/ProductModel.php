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

    public function getProductData($id = null, $fk_category = null)
    {
        $query = $this->db->table('product');

        if(!empty($id))
            $query->where('id', $id);

        if(!empty($fk_category))
            $query->where('fk_category', $fk_category);

        return $query->get()->getResult();
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
        $query = $this->db->table('category')
        ->orderBy('name', 'asc');

        return $query->get()->getResult();
    }

    public function getProducts()
    {
        $query = $this->db->table('product')
        ->orderBy('name', 'asc');

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