<?php

class Settings_migratemodel extends CI_Model {

    private $tbl_ticket = 'Ticket';
    private $tbl_ticketrow = 'TicketRules';
    private $tbl_invoice = 'Invoice';
    private $tbl_invoiceR = 'InvoiceRules';
    private $tbl_invoiceS = 'InvoiceSupplier';
    private $tbl_invoiceRS = 'InvoiceRulesSupplier';
    private $tbl_invoiceP = 'Product';
    private $tbl_webshop = 'Webshop';
    private $old;

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->old = $this->load->database('old', TRUE);
    }

    function getOldBusiness($businessId) {
        $this->old->where('id', $businessId);
        $this->old->from('bedrijf');
        return $this->old->get();
    }

    function removeNewBusiness($businessId) {
        $this->db->where('Id', $businessId);
        $this->db->delete('Business');
    }

    function insertNewBusiness($data) {
        $this->db->insert('Business', $data);
        return $this->db->insert_id();
    }

    function getOldCustomers($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('klanten');
        return $this->old->get();
    }

    function removeNewCustomers($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Customers');
    }

    function insertNewCustomer($data) {
        $this->db->insert('Customers', $data);
        return $this->db->insert_id();
    }

    function getOldSuppliers($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('leveranciers');
        return $this->old->get();
    }

    function removeNewSuppliers($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Supplier');
    }

    function insertNewSupplier($data) {
        $this->db->insert('Supplier', $data);
        return $this->db->insert_id();
    }

    function getOldInvoicesS($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('inkopen');
        return $this->old->get();
    }

    function getOldInvoiceRulesS($invoiceNumber) {
        $this->old->where('factuurnummer', $invoiceNumber);
        $this->old->from('inkoop');
        return $this->old->get();
    }

    function removeNewInvoiceS($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('InvoiceSupplier');
    }

    function removeNewInvoiceRulesS($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('InvoiceRulesSupplier');
    }

    function removeNewInvoiceRuleS($ruleId) {
        $this->db->where('Id', $ruleId);
        $this->db->delete('InvoiceRulesSupplier');
    }

    function insertNewInvoiceS($data) {
        $this->db->insert('InvoiceSupplier', $data);
        return $this->db->insert_id();
    }

    function insertNewInvoiceRuleS($data) {
        $this->db->insert('InvoiceRulesSupplier', $data);
        return $this->db->insert_id();
    }

    function removeNewUsers($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('User');
    }

    function getOldUsers($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('gebruiker');
        return $this->old->get();
    }

    function insertnewUser($data) {
        $this->db->insert('User', $data);
        return $this->db->insert_id();
    }

    function getOldContacts($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('contactpersonen');
        return $this->old->get();
    }

    function removeNewContacts($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Contacts');
    }

    function insertNewContactS($data) {
        $this->db->insert('ContactsS', $data);
        return $this->db->insert_id();
    }

    function getOldContactsS($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('contactpersonenl');
        return $this->old->get();
    }

    function removeNewContactsS($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('ContactsS');
    }

    function insetNewContact($data) {
        $this->db->insert('Contacts', $data);
        return $this->db->insert_id();
    }

    function removeNewWarehouses($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Warehouse');
    }

    function getOldWarehouses($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('magazijnen');
        return $this->old->get();
    }

    function insertNewWarehouse($data) {
        $this->db->insert('Warehouse', $data);
        return $this->db->insert_id();
    }

    function removeNewContactmoment($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('ContactMoment');
    }

    function getOldContactMoment($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('contactmoment');
        return $this->old->get();
    }

    function insertNewContactmoment($data) {
        $this->db->insert('ContactMoment', $data);
        return $this->db->insert_id();
    }

    function removeNewStatus($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Status');
    }

    function getOldStatus($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('status');
        return $this->old->get();
    }

    function insertNewStatus($data) {
        $this->db->insert('Status', $data);
        return $this->db->insert_id();
    }

    function removeOldProjects($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Projects');
    }

    function getOldProjects($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('projecten');
        return $this->old->get();
    }

    function insertNewProject($data) {
        $this->db->insert('Projects', $data);
        return $this->db->insert_id();
    }

    function getOldTickets($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('incidenten');
        return $this->old->get();
    }

    function getOldTicketRules($ticketNumber) {
        $this->old->where('nummer', $ticketNumber);
        $this->old->from('incident');
        return $this->old->get();
    }

    function removeNewInvoiceC($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Invoice');
    }

    function removeNewInvoiceRulesC($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('InvoiceRules');
    }

    function getOldInvoicesC($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('facturen');
        return $this->old->get();
    }

    function getOldInvoiceRulesC($invoiceNumber) {
        $this->old->where('factuur_nmr', $invoiceNumber);
        $this->old->from('factuur');
        return $this->old->get();
    }

    function insertNewInvoiceC($data) {
        $this->db->insert('Invoice', $data);
        return $this->db->insert_id();
    }

    function insertNewInvoiceRuleC($data) {
        $this->db->insert('InvoiceRules', $data);
        return $this->db->insert_id();
    }

    function insertNewTicketRule($data) {
        $this->db->insert('TicketRules', $data);
        return $this->db->insert_id();
    }

    function insertNewTicket($data) {
        $this->db->insert('Ticket', $data);
        return $this->db->insert_id();
    }

    function removeNewTicketRules($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('TicketRules');
    }

    function removeNewTickets($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Ticket');
    }

    function getAllTicket($BusinessId) {
        $this->db->where('BusinessId', $BusinessId);
        $this->db->from($this->tbl_ticket);
        return $this->db->get();
    }

    function getIncident($incidentId) {
        $this->db->where('id', $incidentId);
        $this->db->from('incident');
        return $this->db->get();
    }

    function getAllTicketRules($ticketNumber) {
        $this->db->where('Number', $ticketNumber);
        $this->db->from($this->tbl_ticketrow);
        return $this->db->get();
    }

    function updateTicketRule($data, $TicketRuleId) {
        $this->db->where('Id', $TicketRuleId);
        $this->db->update($this->tbl_ticketrow, $data);
    }

    function updateTicket($data, $ticketId) {
        $this->db->where('Id', $ticketId);
        $this->db->update($this->tbl_ticket, $data);
    }

    function getAlInvoices($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoice);
        return $this->db->get();
    }

    function getInvoiceRules($invoiceNumber) {
        $this->db->where('InvoiceNumber', $invoiceNumber);
        $this->db->from($this->tbl_invoiceR);
        return $this->db->get();
    }

    function updateInvoiceRule($invoiceRuleId, $data) {
        $this->db->where('Id', $invoiceRuleId);
        $this->db->update($this->tbl_invoiceR, $data);
    }

    function updateInvoice($invoiceId, $data) {
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_invoice, $data);
    }

    function getAlInvoicesSupplier($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoiceS);
        return $this->db->get();
    }

    function getInvoiceRulesSupplier($invoiceNumber, $supplierId) {
        $this->db->where('InvoiceNumber', $invoiceNumber);
        $this->db->where('SupplierId', $supplierId);
        $this->db->from($this->tbl_invoiceRS);
        return $this->db->get();
    }

    function updateInvoiceRuleSupplier($invoiceRuleId, $data) {
        $this->db->where('Id', $invoiceRuleId);
        $this->db->update($this->tbl_invoiceRS, $data);
    }

    function updateInvoiceSupplier($invoiceId, $data) {
        $this->db->where('Id', $invoiceId);
        $this->db->update($this->tbl_invoiceS, $data);
    }

    function getAllProduct($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->from($this->tbl_invoiceP);
        return $this->db->get();
    }

    function updateProduct($data, $productId) {
        $this->db->where('Id', $productId);
        $this->db->update($this->tbl_invoiceP, $data);
    }

    function removeNewProducts($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Product');
    }

    function getOldProducts($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('artikelen');
        return $this->old->get();
    }

    function insertNewProduct($data) {
        $this->db->insert('Product', $data);
        return $this->db->insert_id();
    }

    function removeNewProductgroup($businessId) {
        $this->db->where('BusinessId', $businessId);
        $this->db->delete('Productgroup');
    }

    function getOldProductgroups($businessId) {
        $this->old->where('bedrijfid', $businessId);
        $this->old->from('productgroep');
        return $this->old->get();
    }

    function insertNewProductgroup($data) {
        $this->db->insert('Productgroup', $data);
        return $this->db->insert_id();
    }
}