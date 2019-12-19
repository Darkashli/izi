<?php

class Attachments_model extends CI_Model {

    private $tbl_parent= 'Attachments';

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function addAttachment($data) {
		return $this->db->insert($this->tbl_parent, $data);
	}

    function getAll($customerId, $BusinessId)
    {
        $this->db->where('CustomerId', $customerId);
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getTicketAttachments($TicketId, $BusinessId)
    {
        $this->db->where('TicketId', $TicketId);
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }

    function getTicketRuleAttachments($TicketRuleId, $BusinessId)
    {
        $this->db->where('TicketRuleId', $TicketRuleId);
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_parent);
        return $this->db->get();
    }
}
