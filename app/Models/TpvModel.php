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

    public function objDelete($table, $id)
    {
        $query = $this->db->table($table)
        ->where('id', $id)
        ->delete();

        return $query->resultID;
    }

    public function getOpenTables()
    {
        $query = $this->db->table('tpv_bar_tables ta')
        ->select('ta.id tableID,
        ta.dateOpen dateOpen,
        ta.tableID tableName,
        e.name,
        e.lastName,
        SUM(p.price) AS price
        ')
        ->where('ta.status', 1)
        ->join('tpv_bar_ticket ti', 'ti.fkTable = ta.id')
        ->join('tpv_bar_product p', 'p.id = ti.fkProduct')
        ->join('tpv_bar_employees e', 'e.id = ta.fkEmployee')
        ->groupBy('ti.fkTable')
        ->orderBy('ta.id', 'desc'); 

        return $query->get()->getResult();
    }

    public function getTables($id)
    {
        $query = $this->db->table('tpv_bar_tables')
        ->where('id', $id);

        return $query->get()->getResult();
    }

    public function getTicketByTable($fkTable)
    {
        $query = $this->db->table('tpv_bar_ticket t')
        ->select('t.id ticketID, 
        p.name,
        p.id productID, 
        p.price price')
        ->join('tpv_bar_product p', 'p.id = t.fkProduct') 
        ->where('t.fkTable', $fkTable)
        ->orderBy('t.id', 'desc');

        return $query->get()->getResult();  
    }

    public function getTableInfo($id)
    {
        $query = $this->db->table('tpv_bar_tables')
        ->where('id', $id);

        return $query->get()->getResult();
    }

}