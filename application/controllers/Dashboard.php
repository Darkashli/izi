<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('Ticket');
        $this->load->helper('cookie');
        $this->load->helper('user');
        $this->load->helper('quotation');
        $this->load->helper('project');
        $this->load->helper('repeatingInvoice');
        $this->load->helper('webshop');
        $this->load->library('session');
        $this->load->model('tickets/Tickets_model', '', TRUE);
        $this->load->model('tickets/Tickets_statusmodel', '', TRUE);
        $this->load->model('results/Results_model', '', TRUE);
        $this->load->model('users/Users_model', '', TRUE);
        $this->load->model('salesorders/SalesOrder_model', '', TRUE);
        $this->load->model('quotations/Quotation_model', '', TRUE);
        $this->load->model('projects/Project_model', '', TRUE);
        $this->load->model('customers/Customers_model', '', TRUE);
        $this->load->model('webshop/Webshop_model', '', TRUE);
    }

    public function index() {

        if (!isLogged()) {
            redirect('login');
        }
        $this->load->library('Subquery');

        $tickets = "";

        $businessId = $this->session->userdata('user')->BusinessId;
        $latestStatus = $this->Tickets_statusmodel->getLatestStatus($businessId)->row();
        $firstStatus = $this->Tickets_statusmodel->getFirstStatus($businessId)->row();

        $countTickets['open'] = 0;
        $countTickets['closed'] = 0;
        $countTickets['new'] = 0;
        $countTickets['invoiced'] = 0;
        $ticketsinvoiced = array();
        $postUserId = $this->session->userdata('user')->Id;

        $openOrders = $this->SalesOrder_model->getOpenPrintedOrders(0, $businessId)->result();

        if (checkModule('ModuleTickets') && $latestStatus != null) {
            $from = $this->input->post('begin_date') ? strtotime($this->input->post('begin_date')) : strtotime(date('Y-m-d'));
            $to = $this->input->post('end_date') ? strtotime($this->input->post('end_date')) : strtotime(date('Y-m-d'));
            $postUserId = $this->input->post('userFilter') ? $this->input->post('userFilter') : $this->session->userdata('user')->Id;

            $data['users'] = $this->Users_model->getAll($businessId)->result();
            $tickets = $this->Tickets_model->getOpenNotInvoiced($businessId, $latestStatus->Id, $postUserId)->result();
            $data['latestStatus'] = $latestStatus;

            $countTickets['open'] = $this->Tickets_model->getCountTicketsR($businessId, 1, ($this->input->post('show') == 'show_all') ? NULL : $postUserId, $from, $to);
            $countTickets['closed'] = $this->Tickets_model->getCountTicketsR($businessId, 6, ($this->input->post('show') == 'show_all') ? NULL : $postUserId, $from, $to);
            $countTickets['new'] = $this->Tickets_model->getCountNewTickets($businessId, ($this->input->post('show') == 'show_all') ? NULL : $postUserId);

            $countInvoice = $this->Tickets_model->getCountInvoice($businessId, $from, $to);
            
            foreach ($countInvoice as $key => $value) {
                foreach (unserialize($value->WorkOrder) as $unserializedTickets) {
                    if ($this->Tickets_model->getTicketUserGrouped($businessId, $unserializedTickets, ($this->input->post('show') == 'show_all') ? NULL : $postUserId)->row() != NULL) {
                        $ticketsinvoiced[] = $this->Tickets_model->getTicketUserGrouped($businessId, $unserializedTickets, ($this->input->post('show') == 'show_all') ? NULL : $postUserId)->row();
                    }
                }
            }
        }
        
        if (checkModule('ModuleTickets')) {
            $data['projects'] = $this->Project_model->getAll(0, $businessId)->result();
        }
        
        if (checkModule('ModuleQuotation')) {
            $latestQuotationStatus = getLatestQiotationStatus($businessId);
            $data['quotations'] = $this->Quotation_model->getAllStatusNot($latestQuotationStatus->Key, 'all', $businessId)->result();
            $data['latestQuotationStatus'] = $latestQuotationStatus;
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }
        
        // Repeating invoices
        $RPIdate = strtotime(date('d-m-Y'));
        $RPItimePeriod = array() + unserialize(TIMEPERIOD);
        $RPIselected = 0;

        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
          if (isset($_POST['period']) && !empty($_POST['period']))
          {
            $RPIselected = $_POST['period'];
            $RPIdate = strtotime("+ " . $_POST['period'], $RPIdate);
          }
        }
        
        $data['businessId'] = $businessId;
        $data['tickets'] = $tickets;
        $data['countTickets'] = $countTickets;
        $data['countTickets']['invoiced'] = count($ticketsinvoiced);
        $data['latestStatus'] = $latestStatus;
        $data['firstStatus'] = $firstStatus;
        $data['postUserId'] = $postUserId;
        $data['orders'] = $openOrders;
        $data['RPIperiod'] = form_dropdown('period', $RPItimePeriod, $RPIselected, CLASSSELECTBOOTSTRAPSUBMIT);
        $data['repeatingInvoices'] = $this->Customers_model->getAllRepeatingInvoiceBelowDate($RPIdate, $businessId)->result();
        $data['webshop'] = $this->Webshop_model->getWebshop($businessId)->row();
        
        $this->load->view('dashboard/default', $data);
    }

}
