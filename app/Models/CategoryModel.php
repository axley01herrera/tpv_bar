<?php

namespace App\Models;

use CodeIgniter\CLI\Console;
use CodeIgniter\Model;
use CodeIgniter\Database\MySQLi\Builder;

class CategoryModel extends Model
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

    public function updateCat($data, $id)
    {
        $return = array();

        $query = $this->db->table('category')
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

    public function getAllCategories()
    {
        $db = db_connect();
        $builder = $db->table('category');
        $query = $builder->get();
        return $query->getResult();
    }

    public function checkCatExist($nameCat, $id = '')
    {
        $query = $this->db->table('category')
            ->where('nameCat', $nameCat);

        if (!empty($id)) {
            $IDs = array();
            $IDs[0] = $id;
            $query->whereNotIn('id', $IDs);
        }

        return $query->get()->getResult();
    }



}