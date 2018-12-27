<?php

/**
 * Created by PhpStorm.
 * User: tuananh
 * Date: 24/10/2017
 * Time: 09:31
 */
class Mbase extends CI_Model
{
    protected $_table;
    protected $_id = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function count() {
        $this->db->from($this->_table);
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function delete($id)
    {
        $this->db->where($this->_id, $id);

        return $this->db->delete($this->_table);
    }

    public function save($params)
    {
        if(is_array($params)) {
            $params = (object) $params;
        }

        if($this->db->insert($this->_table, $params)) {
            return $this->db->insert_id();
        }

        return false;
    }

    public function update($params)
    {
        if(is_array($params)) {
            $params = (object) $params;
        }

        return $this->db->update($this->_table, $params, [
            $this->_id => $params->{$this->_id}
            ]
        );
    }

    public function load($id)
    {
        $this->db->from($this->_table);
        $this->db->where($this->_id, $id);
        $query = $this->db->get();

        if($query->num_rows()) {
            return $query->row_array();
        }

        return false;
    }

    public function all($orderBy = '')
    {
        $this->db->from($this->_table);
        if($orderBy) {
            $this->db->order_by($orderBy);
        }

        $query = $this->db->get();

        return $query->result_array();
    }
}
