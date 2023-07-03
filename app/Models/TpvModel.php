<?php

namespace App\Models;

use CodeIgniter\Model;

class TpvModel extends Model
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

    public function getOpenTables()
    {
        $query = $this->db->table('tables')
        ->where('status', 1);

        return $query->get()->getResult();
    }
}