<?php

class Business_model extends CI_Model {
    
    private $tbl_parent= 'Business';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    
    function getAll(){
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getBusiness($BusinessId){
        $this->db->where('Id', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function createBusiness($data){
        $this->db->insert('Business', $data);
    }
    
    function updateBusiness($data, $businessId){
        $this->db->where('Id', $businessId);
        $this->db->update('Business', $data);
    }

}