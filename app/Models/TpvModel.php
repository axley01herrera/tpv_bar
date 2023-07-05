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

    public function objDelete($table, $id)
    {
        $query = $this->db->table($table)
        ->where('id', $id)
        ->delete();

        return $query->resultID;
    }

    public function getOpenTables()
    {
        $query = $this->db->table('tables ta')
        ->select('ta.id tableID,
        ta.dateOpen dateOpen,
        ta.tableID tableName,
        COUNT(p.id) AS products,
        SUM(p.price) AS price
        ')
        ->where('ta.status', 1)
        ->join('ticket ti', 'ti.fkTable = ta.id')
        ->join('product p', 'p.id = ti.fkProduct')
        ->groupBy('ti.fkTable')
        ->orderBy('ta.id', 'desc');

        // var_dump($query->get()->getResult()); exit();

        return $query->get()->getResult();
    }

    public function getTables($id)
    {
        $query = $this->db->table('tables')
        ->where('id', $id);

        return $query->get()->getResult();
    }

    public function getTicketByTable($fkTable)
    {
        $query = $this->db->table('ticket t')
        ->select('t.id ticketID, 
        p.name,
        p.id productID, 
        p.price price')
        ->join('product p', 'p.id = t.fkProduct') 
        ->where('t.fkTable', $fkTable)
        ->orderBy('t.id', 'desc');

    
        return $query->get()->getResult();  
    }
}