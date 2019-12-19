<?php

class Tickets_model extends CI_Model {

	private $tbl_parent = 'Ticket';
	private $tbl_parentR = 'TicketRules';

	public function __construct() {
		// Call the CI_Model constructor
		parent::__construct();
	}

	function getAll($customerId, $BusinessId) {
		$this->db->join($this->tbl_parentR, "$this->tbl_parent.LatestTicketRule = $this->tbl_parentR.Id", 'FULL');

		$this->db->where("$this->tbl_parent.CustomerId", $customerId);
		$this->db->where("$this->tbl_parent.BusinessId", $BusinessId);

		$this->db->select("
			$this->tbl_parentR.`TicketId`,
			$this->tbl_parentR.`Number`,
			$this->tbl_parentR.`Status`,
			$this->tbl_parentR.`CustomerId`,
			$this->tbl_parentR.`Date`,
			$this->tbl_parentR.`ContactId`,
			$this->tbl_parentR.`UserIdLink`,
			$this->tbl_parent.`Description`,
			$this->tbl_parent.`Status` AS TicketStatus,
			$this->tbl_parent.`Priority`
		");

		return $this->db->get($this->tbl_parent);
	}

	function getCountTicketsR(
		$BusinessId,
		$status,
		$userId = NULL,
		$from = NULL,
		$to = NULL
	){
		$this->db->select("MAX($this->tbl_parentR.Id)");

		$this->db->where("$this->tbl_parentR.BusinessId", $BusinessId);

		if ($userId !== NULL) 	{ $this->db->where('UserIdLink', $userId); }
		if ($status == 1) 		{ $this->db->where("$this->tbl_parentR.`Status` != ", 6); } // If status is open, count the others.
		else 					{ $this->db->where("$this->tbl_parentR.`Status`", $status); }
		if ($from 	!== NULL) 	{ $this->db->where("$this->tbl_parentR.`Date` >=", $from); }
		if ($to 	!== NULL) 	{ $this->db->where("$this->tbl_parentR.`Date` <=", $to); }

		$this->db->group_by("$this->tbl_parentR.TicketId");

		return $this->db->count_all_results($this->tbl_parentR);
	}

	function getCountNewTickets( $BusinessId, $userId = NULL ){
		$this->db->select('MIN(`TicketRules2`.Id)');
		$this->db->from("$this->tbl_parentR AS TicketRules2");
		$this->db->where("`TicketRules2`.TicketId = $this->tbl_parentR.TicketId");
		$TicketRulesId = $this->db->get_compiled_select();
		$this->db->reset_query();

		$this->db->where("$this->tbl_parentR.BusinessId", $BusinessId);
		if ($userId 	!== NULL) {
			$this->db->where("$this->tbl_parentR.UserIdLink", $userId);
		}
		$this->db->where("$this->tbl_parentR.`Date`", strtotime(date('Y-m-d')));
		$this->db->where("$this->tbl_parentR.`Id` = ($TicketRulesId)");
		$this->db->group_by('TicketId');

		return $this->db->count_all_results("$this->tbl_parentR AS TicketRules");
	}

	function getCountInvoice($businessId, $from = NULL, $to = NULL)
	{
		$this->db->where('Invoice.BusinessId', $businessId);
		$this->db->where('Invoice.WorkOrder <> ', '0');

		if ($from 	!== NULL) { $this->db->where('Invoice.InvoiceDate >=', $from); }
		if ($to 	!== NULL) { $this->db->where('Invoice.InvoiceDate <=', $to); }

		return $this->db->get('Invoice')->result();
	}

	function getCustomer($CustomerId, $businessId) {
		$this->db->where('CustomerId', $CustomerId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getContact($contactId) {
		$this->db->where('Id', $contactId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function createTicket($data) {
		$this->db->insert($this->tbl_parent, $data);
		return $this->db->insert_id();
	}

	function updateTicket($ticketId, $data) {
		$this->db->where('Id', $ticketId);
		$this->db->update($this->tbl_parent, $data);
	}

	function createTicketR($data) {
		$this->db->insert($this->tbl_parentR, $data);
		return $this->db->insert_id();
	}

	function updateTicketR($data, $ticketRuleId) {
		$this->db->where('Id', $ticketRuleId);
		$this->db->update($this->tbl_parentR, $data);
	}

	function getTicket($ticketId, $businessId, $where = array()) {
		$this->db->where('Id', $ticketId);
		$this->db->where('BusinessId', $businessId);
		$this->db->where($where);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getTicketUserGrouped($businessId, $ticketId, $userId = NULL)
	{
		$this->db->join('TicketRules', 'Ticket.id = TicketRules.TicketId');
		$this->db->where('Ticket.BusinessId', $businessId);
		$this->db->where('Ticket.Id', $ticketId);
		if ($userId !== NULL) {
			$this->db->where('TicketRules.UserIdLink', $userId);
		}
		$this->db->group_by('TicketRules.TicketId');
		$this->db->order_by('TicketRules.TicketId', 'DESC');
		$this->db->order_by('Ticket.Id', 'DESC');
		return $this->db->get($this->tbl_parent);
	}

	function getProgress($progressId, $businessId) {
		$this->db->where('Id', $progressId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	function getBase($progressId) {
		$this->db->where('Id', $progressId);
		$this->db->from($this->tbl_parent);
		return $this->db->get();
	}

	function getTicketRule($ticketRuleId, $ticketId, $businessId) {
		$this->db->where('Id', $ticketRuleId);
		$this->db->where('TicketId', $ticketId);
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	function removeTicketRule($ticketRuleId, $ticketId, $businessId) {
		$this->db->where('Id', $ticketRuleId);
		$this->db->where('TicketId', $ticketId);
		$this->db->where('BusinessId', $businessId);
		$this->db->delete($this->tbl_parentR);
	}

	function getTicketRules($ticketId, $businessId) {
		$this->db->where('TicketId', $ticketId);
		$this->db->where('BusinessId', $businessId);
		$this->db->order_by('Date', 'ASC');
		$this->db->order_by('StartWork', 'ASC');
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	function getTicketRulesOrderBy($ticketId, $businessId) {
		$this->db->select('UserIdLink, NatureOfWorkId');
		$this->db->select_sum('TotalWork');
		$this->db->where('TicketId', $ticketId);

		$this->db->group_by('UserIdLink, NatureOfWorkId', 'desc');
		$this->db->where('BusinessId', $businessId);
		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

	function getConnectedOpenTickets($userId, $statusId) {
		$this->db->order_by('Date', 'DESC');
		$this->db->order_by('StartWork', 'DESC');
		$this->db->from('TicketRules');
		$TicketRulesSorted = $this->db->get_compiled_select();
		$this->db->reset_query();

		$this->db->group_by('TicketId');
		$this->db->from('(' . $TicketRulesSorted . ') AS TicketRulesSorted');
		$TicketRulesSortedGrouped = $this->db->get_compiled_select();
		$this->db->reset_query();

		$this->db->where('TicketRulesSortedGrouped.Status !=', $statusId);
		$this->db->where('TicketRulesSortedGrouped.UserIdLink', $userId);
		$this->db->from('(' . $TicketRulesSortedGrouped . ') AS TicketRulesSortedGrouped');

		$this->db->join('Ticket', 'TicketRulesSortedGrouped.TicketId = Ticket.Id', 'LEFT');
		$this->db->join('User AS User', 'TicketRulesSortedGrouped.UserId = User.Id', 'LEFT');
		$this->db->join('User AS UserL', 'TicketRulesSortedGrouped.UserIdLink = UserL.Id', 'LEFT');

		$this->db->select('
			TicketRulesSortedGrouped.`TicketId`,
			TicketRulesSortedGrouped.`Number`,
			TicketRulesSortedGrouped.`Status`,
			TicketRulesSortedGrouped.`CustomerId`,
			TicketRulesSortedGrouped.`Date`,
			TicketRulesSortedGrouped.`UserId`,
			TicketRulesSortedGrouped.`UserIdLink`,
			Ticket.`Description`,
			User.`FirstName` AS UserFirstname,
			User.`Insertion` AS UserInsertion,
			User.`LastName` AS UserLastname,
			UserL.`FirstName` AS UserLinkFirstname,
			UserL.`Insertion` AS UserLinkInsertion,
			UserL.`LastName` AS UserLinkLastname
		');

		return $this->db->get();
	}

	function getConnectedOpenTicketsOLD($userId, $statusId) {
		$sub = $this->subquery->start_subquery('from');
		$sub->order_by('Date', 'DESC');
		$sub->order_by('StartWork', 'DESC');
		$sub->from('TicketRules');
		$this->subquery->end_subquery('TicketRulesSorted', TRUE);

		$this->db->group_by('TicketId');

		$this->db->where('UserIdLink', $userId);
		$this->db->where('TicketRulesSorted.Status !=', $statusId);
		//$this->db->

		$this->db->join('Ticket', 'TicketRulesSorted.TicketId = Ticket.Id', 'LEFT');

		$this->db->select('TicketRulesSorted.TicketId, TicketRulesSorted.Status, TicketRulesSorted.UserIdLink, TicketRulesSorted.CustomerId, Ticket.Description, TicketRulesSorted.Date, Ticket.Number');
		//$this->db->from($this->tbl_parent);

		return $this->db->get();
	}

	function getOpenNotInvoiced($businessId, $closedStatus, $userId) {
		$this->db->join($this->tbl_parentR, "$this->tbl_parent.LatestTicketRule = $this->tbl_parentR.Id", 'FULL');

		$this->db->where("$this->tbl_parentR.Status !=", $closedStatus);
		$this->db->where("$this->tbl_parent.BusinessId", $businessId);
		$this->db->where("$this->tbl_parent.Status", '0');
		return $this->db->get($this->tbl_parent);
	}

	function getClosedNotInvoiced($businessId, $closedStatus) {
		$this->db->join($this->tbl_parentR, "$this->tbl_parent.LatestTicketRule = $this->tbl_parentR.Id", 'FULL');

		$this->db->where("$this->tbl_parentR.Status", $closedStatus);
		$this->db->where("$this->tbl_parent.BusinessId", $businessId);
		$this->db->where("$this->tbl_parent.Status", '0');
		return $this->db->get($this->tbl_parent);
	}

	function getActivityBetweetDate($startDate, $endDate, $userId, $businessId) {
		$this->db->where('UserId', $userId);
		//$this->db->where('TotalWork >', '0');
		$this->db->where("Date BETWEEN $startDate AND $endDate");
		$this->db->where('TicketRules.BusinessId', $businessId);
		$this->db->order_by('Date', 'DESC');

		$this->db->join('Ticket', 'TicketId = Ticket.Id', 'LEFT');

		$this->db->select('TicketRules.`Id`,
		TicketRules.`TicketId`,
	TicketRules.`Status`,
	TicketRules.`CustomerId`,
	TicketRules.`Date`,
		TicketRules.`StartWork`,
		TicketRules.`TotalWork`,
		TicketRules.`EndWork`,
		TicketRules.`ActionUser`,
		Ticket.`Status` AS TicketStatus');

		$this->db->from($this->tbl_parentR);
		return $this->db->get();
	}

}
