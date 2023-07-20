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

    # CONFIG

    public function getConfigData()
    {
        return $this->db->table('tpv_bar_configuration')
            ->get()
            ->getResult();
    }

    # DASHBOARD

    public function getCollectionDay()
    {
        $query = $this->db->table('tpv_bar_tables')
            ->where('status', 0)
            ->where('date', date('Y-m-d'));

        $data = $query->get()->getResult();
        $count = sizeof($data);

        $cash = 0;
        $card = 0;

        for ($i = 0; $i < $count; $i++) {

            if ($data[$i]->payType == 1) // CASH
                $cash = $cash + $data[$i]->amount;
            elseif ($data[$i]->payType == 2) // CARD
                $card = $card + $data[$i]->amount;
        }

        $return = array();
        $return['cash'] = $cash;
        $return['card'] = $card;

        return $return;
    }

    public function getChartWeek()
    {
        $firstDayOfWeek = date('Y-m-d', strtotime('monday this week')); // Get the first day of the week (Monday) v
        $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week')); // Get the last day of the week (Sunday) 

        $query = $this->db->table('tpv_bar_tables')
            ->where('status', 0)
            ->where('date >=', $firstDayOfWeek)
            ->where('date <=', $lastDayOfWeek);

        $data = $query->get()->getResult();
        $countData = sizeof($data);

        $serie['mon'] = 0;
        $serie['tue'] = 0;
        $serie['wed'] = 0;
        $serie['thu'] = 0;
        $serie['fri'] = 0;
        $serie['sat'] = 0;
        $serie['sun'] = 0;
        $serie['total'] = 0;

        $firstDay = date('Y-m-d', strtotime($firstDayOfWeek));

        for ($i = 0; $i < $countData; $i++) {

            if ($firstDay == $data[$i]->date) $serie['mon'] = $serie['mon'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+1 day')) == $data[$i]->date) $serie['tue'] = $serie['tue'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+2 day')) == $data[$i]->date) $serie['wed'] = $serie['wed'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+3 day')) == $data[$i]->date) $serie['thu'] = $serie['thu'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+4 day')) == $data[$i]->date) $serie['fri'] = $serie['fri'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+5 day')) == $data[$i]->date) $serie['sat'] = $serie['sat'] + $data[$i]->amount;
            elseif (date('Y-m-d', strtotime($firstDay . '+6 day')) == $data[$i]->date) $serie['sun'] = $serie['sun'] + $data[$i]->amount;
        }

        $serie['total'] = $serie['total'] + $serie['mon'] + $serie['tue'] + $serie['wed'] + $serie['thu'] + $serie['fri'] + $serie['sat'] + $serie['sun'];

        return $serie;
    }

    public function getChartMont($year)
    {
        $firstDay = date('Y-m-d', strtotime("$year-01-01"));
        $lastDay = date('Y-m-d', strtotime("$year-12-31"));

        $query = $this->db->table('tpv_bar_tables')
            ->where('status', 0)
            ->where('date >=', $firstDay)
            ->where('date <=', $lastDay);

        $data = $query->get()->getResult();
        $countData = sizeof($data);
        $total = 0;

        for ($month = 1; $month <= 12; $month++) {

            $serie[$month] = 0;
            $mont = date("F", mktime(0, 0, 0, $month, 1, $year));
            $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));

            for ($day = 1; $day <= $daysInMonth; $day++) {

                for ($i = 0; $i < $countData; $i++) {
                    if (date('Y-m-d', strtotime($day . '-' . $mont . '-' . $year)) == $data[$i]->date)
                        $serie[$month] = $serie[$month] + $data[$i]->amount;
                }
            }
            $total = $total + $serie[$month];
        }

        $serie['total'] = $total;

        return $serie;
    }

    public function getHistoryProcessingData($params)
    {
        $query = $this->db->table('tpv_bar_table_history');

        if (!empty($params['search'])) {
            $query->like('tableName', $params['search']);
            $query->orLike('dateOpen', $params['search']);
            $query->orLike('dateClose', $params['search']);
            $query->orLike('employeeName', $params['search']);
            $query->orLike('employeeLastName', $params['search']);
            $query->orLike('payTypeLabel', $params['search']);
            $query->orLike('amount', $params['search']);
        }

        $query->offset($params['start']);
        $query->limit($params['length']);
        $query->orderBy($this->getHistoryProcessingSort($params['sortColumn'], $params['sortDir']));

        return $query->get()->getResult();
    }

    public function getHistoryProcessingSort($column, $dir)
    {
        $sort = '';

        if ($column == 0) {
            if ($dir == 'asc')
                $sort = 'tableName ASC';
            else
                $sort = 'tableName DESC';
        }

        if ($column == 1) {
            if ($dir == 'asc')
                $sort = 'dateOpen ASC';
            else
                $sort = 'dateOpen DESC';
        }

        if ($column == 2) {
            if ($dir == 'asc')
                $sort = 'dateClose ASC';
            else
                $sort = 'dateClose DESC';
        }

        if ($column == 3) {
            if ($dir == 'asc')
                $sort = 'employeeName ASC';
            else
                $sort = 'employeeName DESC';
        }

        if ($column == 4) {
            if ($dir == 'asc')
                $sort = 'payTypeLabel ASC';
            else
                $sort = 'payTypeLabel DESC';
        }

        if ($column == 5) {
            if ($dir == 'asc')
                $sort = 'amount ASC';
            else
                $sort = 'amount DESC';
        }

        return $sort;
    }

    public function getTotalHistory()
    {
        $query = $this->db->table('tpv_bar_table_history')
            ->selectCount('tableID')
            ->get()->getResult();

        return $query[0]->tableID;
    }
}
