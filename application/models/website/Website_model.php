<?php

class Website_model extends CI_Model {

    private $tbl_parent = 'Website';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($customerId) {
        $this->db->where('CustomerId', $customerId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getWebsite($websiteId, $businessId) {
        $this->db->where('Id', $websiteId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function update($websiteId, $data) {
        $this->db->where('Id', $websiteId);
        $this->db->update($this->tbl_parent, $data);
    }

    function create($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

    function deleteWebsite($websiteId, $businessId) {
        $this->db->where('Id', $websiteId);
        $this->db->where('BusinessId', $businessId);
        $this->db->delete($this->tbl_parent);
    }

}
