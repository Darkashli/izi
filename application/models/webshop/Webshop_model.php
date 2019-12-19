<?php

class Webshop_model extends CI_Model {

    private $tbl_parent = 'Webshop';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getWebshop($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function updateWebshop($webshopId, $data) {
        $this->db->where('Id', $webshopId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createWebshop($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
}
