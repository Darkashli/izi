<?php

class Users_model extends CI_Model {

    private $tbl_parent = 'User';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getUser($userId, $businessId) {
        $this->db->where('Id', $userId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateUser($userId, $data) {
        $this->db->where('Id', $userId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createUser($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
   

}
