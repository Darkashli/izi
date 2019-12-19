<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getLastTicketContact($ticketId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('TicketId', $ticketId);
    $ci->db->order_by("Date", "desc");
    $ci->db->order_by("StartWork", "desc");
    $ci->db->limit(1);

    $query = $ci->db->get('TicketRules');

    $contactId = 0;
    foreach ($query->result() as $row):
        //get the full name by concatinating the first and last names
        $contactId = $row->ContactId;
    endforeach;

    return $contactId;
}

function getFirstTicketRule($ticketId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('TicketId', $ticketId);
    $ci->db->order_by("Date", "asc");
    $ci->db->order_by("StartWork", "asc");
    $ci->db->limit(1);

    $query = $ci->db->get('TicketRules');

    /* $ticketRuleId = 0;
      foreach ($query->result() as $row):
      //get the full name by concatinating the first and last names
      $ticketRuleId = $row->Id;
      endforeach; */
    return $query->row();
}

function getLastTicketRule($ticketId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('TicketId', $ticketId);
    $ci->db->order_by("Date", "desc");
    $ci->db->order_by("StartWork", "desc");
    $ci->db->order_by("Id", "desc");
    $ci->db->limit(1);

    $query = $ci->db->get('TicketRules');

    /* $ticketRuleId = 0;
      foreach ($query->result() as $row):
      //get the full name by concatinating the first and last names
      $ticketRuleId = $row->Id;
      endforeach; */
    return $query->row();
}

function getStatus($statusId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $statusId);
    $ci->db->limit(1);

    $query = $ci->db->get('Status');

    /* $ticketRuleId = 0;
      foreach ($query->result() as $row):
      //get the full name by concatinating the first and last names
      $ticketRuleId = $row->Id;
      endforeach; */

    return $query->row();
}

function getLatestStatus($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('Order', 'DESC');
    $ci->db->limit(1);

    $query = $ci->db->get('Status');
    return $query->row();
}

function getStatusDropdown($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);

    $ci->db->order_by('Order', 'ASC');

    $query = $ci->db->get('Status');

    foreach ($query->result() as $row):
        $statuses[$row->Id] = $row->Description;
    endforeach;

    return $statuses;
}

function getStatusBlock($statusId)
{
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $statusId);
    $ci->db->limit(1);

    $query = $ci->db->get('Status');

    $status = $query->row();
    
    return "<i class=\"status-block\" style=\"background-color: $status->Color\"></i>";
}

function getNatureOfWorkDropdown($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);
    $ci->db->order_by('Description', 'ASC');

    $query = $ci->db->get('NatureOfWork');

    $natureOfWork = array();

    foreach ($query->result() as $row):
        $natureOfWork[$row->Id] = $row->Description;
    endforeach;

    return $natureOfWork;
}

function getNatureOfWork($natureOfWorkdId){
    
    $natureOfWork = "";
    
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('Id', $natureOfWorkdId);
    
    $result = $ci->db->get('NatureOfWork')->row();
    
    if($result != null){
        $natureOfWork = $result->Description;
    }
    
    return $natureOfWork;
}

function getContactMomentDropdown($businessId) {
    $ci = & get_instance();
    $ci->load->database();

    $ci->db->where('BusinessId', $businessId);

    $query = $ci->db->get('ContactMoment');
	
	$natureOfWork = array();

    foreach ($query->result() as $row):
        $natureOfWork[$row->Id] = $row->Description;
    endforeach;

    return $natureOfWork;
}

function trim_text($input, $length = 40, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= ' [...]';
    }

    return $trimmed_text;
}

function getProjectFromPhase($phaseId)
{
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->where('Id', $phaseId);
    $phase = $ci->db->get('ProjectPhase')->row();
    
    if ($phase == null) {
        return null;
    }
    
    $ci->db->reset_query();
    
    $ci->db->where('Id', $phase->ProjectId);
    return $ci->db->get('Project')->row();
}

function getProjectphaseTickets($phaseId)
{
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->join('TicketRules', "Ticket.LatestTicketRule = TicketRules.Id", 'FULL');
    
    $ci->db->select("
        TicketRules.`TicketId`,
        TicketRules.`Number`,
        TicketRules.`Status`,
        TicketRules.`CustomerId`,
        TicketRules.`Date`,
        TicketRules.`ContactId`,
        TicketRules.`UserIdLink`,
        Ticket.`CustomerNotification`,
        Ticket.`Prognosis`,
        Ticket.`Description`,
        Ticket.`Status` AS TicketStatus,
        Ticket.`Priority`
    ");
    
    $ci->db->order_by('Ticket.Number', 'ASC');
    
    $ci->db->where('PhaseId', $phaseId);
    
    return $ci->db->get('Ticket')->result();
}

function getSumTotalwork($ticketId)
{
    $ci = & get_instance();
    $ci->load->database();
    
    $ci->db->select_sum('TotalWork');
    $ci->db->where('TicketId', $ticketId);
    
    $query = $ci->db->get('TicketRules');
    
    return $query->row()->TotalWork;
}

function getPriority($ticketPriority)
{
    switch ($ticketPriority) {
        case 1:
            $priority = '<span class="text-success">Laag</span>';
            break;
        case 2:
            $priority = '<span class="text-warning">Gemiddeld</span>';
            break;
        case 3:
            $priority = '<span class="text-danger">Hoog</span>';
            break;
        default:
            $priority = 'Onbekend';
            break;
    }
    
    return $priority;
}