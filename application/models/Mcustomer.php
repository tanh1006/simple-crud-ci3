<?php

require_once 'Mbase.php';

class Mcustomer extends Mbase {

    protected $_table = 'customers';

    public function filter($params)
    {
        $this->db->from($this->_table);

        $columns = $params['columns'];
        $orderIndex = $params['order'][0]['column'];
        $orderDir = $params['order'][0]['dir'];
        $orderBy = $columns[$orderIndex]['data'];

        $this->db->order_by($orderBy, $orderDir);

        $limit = $params['length'];
        $start = $params['start'];
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        return $query->result_array();
    }
}