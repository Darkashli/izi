<?php

class Transporter_model extends CI_Model {
    
    private $tbl_parent= 'Transporter';
    private $tbl_parentI = 'Transporter_Import';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }
    
    function getAll($BusinessId){
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    function getTransporter($Transporter_id, $businessId){
        $this->db->where('Transporter_id', $Transporter_id);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
    
    /**
     * @param array $ignoredTransporterIds Ignores transporters. For example: used for the transporter you're currently working in.
     */
    function getAllImport($businessId, $ignoredTransporterIds = array()){
        $this->db->join($this->tbl_parent, "$this->tbl_parent.Transporter_id = $this->tbl_parentI.TransporterId", 'LEFT');
        if (!empty($ignoredTransporterIds)) {
            foreach ($ignoredTransporterIds as $ignoredTransporterId) {
                $this->db->where('TransporterId <>', $ignoredTransporterId);
            }
        }
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentI);
        return $this->db->get();
    }
    
    function getImportByName($name, $businessId){
        $this->db->join($this->tbl_parent, "$this->tbl_parent.Transporter_id = $this->tbl_parentI.TransporterId", 'LEFT');
        $this->db->where('Import', $name);
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_parentI);
        return $this->db->get();
    }
    
    function getTransporterImport($transporterId)
    {
        $this->db->where('TransporterId', $transporterId);
        $this->db->from($this->tbl_parentI);
        return $this->db->get();
    }
    
    function updateTransporter($Transporter_id, $data){
        $this->db->where('Transporter_id', $Transporter_id);
        $this->db->update($this->tbl_parent, $data);
    }
    
    function createTransporter($data){
        $this->db->insert($this->tbl_parent, $data);
        return $this->db->insert_id();
    }
    
    function createTransporterImport($data){
        $this->db->insert($this->tbl_parentI, $data);
        return $this->db->insert_id();
    }
    
    function deleteTransporterImports($transporterId)
    {
        $this->db->where('TransporterId', $transporterId);
        return $this->db->delete($this->tbl_parentI);
    }

}
