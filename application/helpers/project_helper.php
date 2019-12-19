<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Returns the project status.
 *
 * @param int $projectId The project ID.
 * @param int $openId The is of the "open" status.
 * @param int $closedId The is of the "closed" status.
 * @return string The status ("Open", "In behandeling" or "Gesloten").
 *
 */
function getProjectStatus($projectId, $openId, $closedId)
{
	$ci = & get_instance();
    $ci->load->database();
	
	$ci->db->join('TicketRules', "Ticket.LatestTicketRule = TicketRules.Id", 'FULL');
	$ci->db->join('ProjectPhase', "ProjectPhase.Id = Ticket.PhaseId", 'FULL');
    
    $ci->db->select("TicketRules.`Status`");
    
    $ci->db->where('ProjectPhase.ProjectId', $projectId);
    
	$tickets = $ci->db->get('Ticket')->result();
	
	$status = 'Open';
	$closedTickets = 0;
	
	foreach ($tickets as $ticket) {
		if ($ticket->Status != $openId) {
			$status = 'In behandeling';
			if ($ticket->Status == $closedId) {
				$closedTickets++;
			}
		}
	}
	
	// Check if all tickets are closed.
	if ($closedTickets != 0 && $closedTickets == count($tickets)) {
		$status = 'Gesloten';
	}
	
	return $status;
}

/**
 * Returns the status of the project phase.
 *
 * @param int $projectPhaseId The ID of the phase.
 * @param int $openId The is of the "open" status.
 * @param int $closedId The is of the "closed" status.
 * @return string The status ("Open", "In behandeling" or "Gesloten").
 *
 */
function getPhaseStatus($projectPhaseId, $openId, $closedId)
{
	$ci = & get_instance();
    $ci->load->database();
	
	$ci->db->join('TicketRules', "Ticket.LatestTicketRule = TicketRules.Id", 'FULL');
    
    $ci->db->select("TicketRules.`Status`");
    
    $ci->db->where('PhaseId', $projectPhaseId);
    
	$tickets = $ci->db->get('Ticket')->result();
	
	$status = 'Open';
	$closedTickets = 0;
	
	foreach ($tickets as $ticket) {
		if ($ticket->Status != $openId) {
			$status = 'In behandeling';
			if ($ticket->Status == $closedId) {
				$closedTickets++;
			}
		}
	}
	
	// Check if all tickets are closed.
	if ($closedTickets != 0 && $closedTickets == count($tickets)) {
		$status = 'Gesloten';
	}
	
	return $status;
}

function getTotalWorkProject($projectId)
{
	$ci = & get_instance();
    $ci->load->database();
	
	$ci->db->select_sum('TotalWork');
	$ci->db->join('Ticket', 'Ticket.Id = TicketRules.TicketId', 'left');
	$ci->db->join('ProjectPhase', 'ProjectPhase.Id = Ticket.PhaseId', 'left');
	
	$ci->db->where('ProjectPhase.ProjectId', $projectId);
	
	$query = $ci->db->get('TicketRules');
	
	return $query->row()->TotalWork;
}

function getTotalWorkPhase($phaseId)
{
	$ci = & get_instance();
    $ci->load->database();
	
	$ci->db->select_sum('TotalWork');
	$ci->db->join('Ticket', 'Ticket.Id = TicketRules.TicketId', 'left');
	
	$ci->db->where('Ticket.PhaseId', $phaseId);
	
	$query = $ci->db->get('TicketRules');
	
	return $query->row()->TotalWork;
}