<?php

class Natureofwork_model extends CI_Model {

    private $tbl_parent = 'NatureOfWork';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function getAll($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getNatureofwork($natureofworkId, $businessId) {
        $this->db->where('Id', $natureofworkId);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function updateNatureofwork($natureofworkId, $data) {
        $this->db->where('Id', $natureofworkId);
        $this->db->update($this->tbl_parent, $data);
    }

    function createNatureofwork($data) {
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }

}
