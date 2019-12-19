<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Work extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('tickets/Tickets_model', '', TRUE);
        $this->load->model('tickets/Tickets_statusmodel', '', TRUE);
        $this->load->model('tickets/Tickets_productmodel', '', TRUE);
        $this->load->model('tickets/Attachments_model', '', TRUE);
        $this->load->model('customers/Customers_model', '', TRUE);
        $this->load->model('customers/Customers_contactsmodel', '', TRUE);
        $this->load->model('business/Business_model', '', TRUE);
        $this->load->model('product/Product_model', '', TRUE);
        $this->load->model('projects/Project_model', '', TRUE);
        $this->load->model('natureofwork/Natureofwork_model', '', TRUE);
        $this->load->helper('Ticket');
    }

    public function index() {
        redirect('customers/index/work');
    }

    public function create()
    {
        if (!isLogged())
        {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;

        if (getBusiness($businessId)->ModuleTickets == 0)
        {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor deze module', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $userId = $this->session->userdata('user')->Id;
        $customerId = $this->uri->segment(3);

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();
        $business = $this->Business_model->getBusiness($businessId)->row();

        if ($customer == null)
        {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        $contacts = $this->Customers_contactsmodel->getCustomer($customerId, $businessId)->result();
        if ($contacts == null)
        {
            $this->session->set_tempdata('err_message', 'Er zijn geen contactpersonen gevonden', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/edit/" . $customerId);
        }

        $statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
        if ($statusses == NULL) {
            $this->session->set_tempdata('err_message', 'Er zijn geen statussen gevonden', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }
        
        $naturesOfWork = $this->Natureofwork_model->getAll($businessId)->result();
        if ($naturesOfWork == null) {
            $this->session->set_tempdata('err_message', 'Er zijn geen aarden van de werkzaamheden gevonden', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $this->db->trans_start();

            $fileError = array();

            $ticketNumber = "I" . date('ym-dH-is');
            $ticketTime = date('H:i');
            $startWork = strtotime($ticketTime);
            $endWork = strtotime($ticketTime);

            $status = $this->Tickets_statusmodel->getFirstStatus($businessId)->row();

            $data = array(
                'Number' => $ticketNumber,
                'Priority' => $_POST['priority'],
                'Description' => $_POST['description'],
                'CustomerNotification' => $_POST['customerNotification'],
                'Prognosis' => $_POST['prognosis'],
                'PhaseId' => $_POST['project_phase'],
                'CustomerId' => $customer->Id,
                'BusinessId' => $businessId
            );

            $ticketId = $this->Tickets_model->createTicket($data);
            $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

            $countFiles = count($_FILES['upload']['name']);
            $countNames = count($_POST['FileDescription']);

            $dataR = array(
                'TicketId' => $ticketId,
                'Number' => $ticketNumber,
                'UserId' => $_POST['user'],
                'UserIdLink' => $_POST['userLink'],
                'StartWork' => $startWork,
                'EndWork' => $endWork,
                'ContactId' => $_POST['contact'],
                'Date' => strtotime(date('d-m-Y')),
                'Status' => $status->Id,
                'NatureOfWorkId' => $_POST['natureofwork'],
                'ContactMomentId' => $_POST['contactMoment'],
                'InternalNote' => $_POST['internalNote'],
                'TotalWork' => $startWork - $endWork,
                'CustomerId' => $customer->Id,
                'BusinessId' => $businessId
            );
            $ticketRuleId = $this->Tickets_model->createTicketR($dataR);

            $this->_Attachments_upload($_FILES, $ticket, $ticketRuleId);

            unset($data);

            $data = array(
                'LatestTicketRule' => $ticketRuleId
            );
            $this->Tickets_model->updateTicket($ticketId, $data);

            $this->db->trans_complete();

            $mailResult = true;
            if ($business->WorkEmail)
            {
                if ($dataR['UserId'] != $dataR['UserIdLink'] && $userId != $dataR['UserIdLink'])
                {
                    // Verstuur e-mail.

                    $this->load->helper('mail');
                    $mailResult = sendNewTicket(getAccountMail($dataR['UserIdLink']), getAccountName($dataR['UserIdLink']), $ticketId, $ticketRuleId);
                }
            }

            if ($mailResult == false)
            {
                // ERROR
            }
            else
            {

                if (!empty($fileError)) {
                    $this->session->set_tempdata('err_message', $_FILES['upload']['name'][$fileError[0]] . ' ' . 'Kreeg een error', 300);
                    $this->session->set_tempdata('err_messagetype', 'success', 300);
                }

                else
                {
                    $this->session->set_tempdata('err_message', 'Ticket succesvol aangemaakt', 300);
                    $this->session->set_tempdata('err_messagetype', 'success', 300);
                }
                redirect('work/update/' . $ticketId);
            }
        }
        else
        {
            $users = getUserDropdown($businessId);
            $contacts = getContactDropdown($customer->Id, $businessId);

            $data['customer'] = $customer;
            $data['user'] = form_dropdown_disabled('user', $users[0], $userId, CLASSDROPDOWN, $users[1]);
            $data['contact'] = form_dropdown_disabled('contact', $contacts[0], '', CLASSDROPDOWN, $contacts[1]);
            $data['userLink'] = form_dropdown_disabled('userLink', $users[0], $userId, CLASSDROPDOWN, $users[1]);
            $data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), '', CLASSDROPDOWN);
            $data['contactMoment'] = form_dropdown('contactMoment', getContactMomentDropdown($businessId), '', CLASSDROPDOWN);
            $data['projects'] = $this->Project_model->getAll($customerId, $businessId)->result();

            $this->load->view('work/create', $data);
        }
    }

    public function update() {
        if (!isLogged()) {
            redirect('login');
        }

        $ticketId = $this->uri->segment(3);
        $ticketRuleId = ($this->uri->segment(4) ? $this->uri->segment(3) : 1);
        $businessId = $this->session->userdata('user')->BusinessId;
        $business = $this->Business_model->getBusiness($businessId)->row();

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();
        $ticketRule = getFirstTicketRule($ticketId);


        if ($ticket == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $contacts = $this->Customers_contactsmodel->getCustomer($ticket->CustomerId, $businessId)->result();

        if ($contacts == null) {
            $this->session->set_tempdata('err_message', 'Er zijn geen contactpersonen aanwezig', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/edit/" . $ticket->CustomerId);
        }

        $statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
        if ($statusses == NULL) {
            $this->session->set_tempdata('err_message', 'Er zijn geen statussen voor de tickets. Maak eerst een status aan in de instellingen voordat je een ticket toevoegt', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($ticketRuleId != 1) {
            $ticketRule = $this->Tickets_model->getTicketRule($ticketRuleId, $ticketId, $businessId)->row();
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->db->trans_start();

            $fileError = array();

            $this->_Attachments_upload($_FILES, $ticket, $ticketRuleId);

            $data = array(
                'Priority' => $_POST['priority'],
                'Description' => $_POST['description'],
                'CustomerNotification' => $_POST['customerNotification'],
                'Prognosis' => $_POST['prognosis'],
                'PhaseId' => $_POST['project_phase']
            );

            $this->Tickets_model->updateTicket($ticketId, $data);

            $dataRule = array(
                'InternalNote' => $_POST['internalNote']
            );

            $this->Tickets_model->updateTicketR($dataRule, $ticketRule->Id);

            $this->db->trans_complete();

            if (!empty($fileError)) {
                $this->session->set_tempdata('err_message', $_FILES['upload']['name'][$fileError[0]] . ' ' . 'Kreeg een error', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
            }

            else
            {
                $this->session->set_tempdata('err_message', 'Ticket succesvol aangepast', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
            }


            redirect('work/update/' . $ticketId);
        } else {

            if ($this->session->tempdata('err_message')) {
                $data['tpl_msg'] = $this->session->tempdata('err_message');
                $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
                $this->session->unset_tempdata('err_message');
                $this->session->unset_tempdata('err_messagetype');
            }

            $customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();

            $users = getUserDropdown($businessId);
            $contacts = getContactDropdown($customer->Id, $businessId);

            $ticketProject = getProjectFromPhase($ticket->PhaseId);

            $data['ticket'] = $ticket;
            $data['ticketRule'] = $ticketRule;
            $data['customer'] = $customer;
            $data['attachments'] = $this->Attachments_model->getTicketAttachments($ticket->Id, $businessId)->result();
            $data['user'] = form_dropdown_disabled('user', $users[0], $ticketRule->UserId, CLASSDROPDOWN);
            $data['contact'] = form_dropdown_disabled('user', $contacts[0], $ticketRule->ContactId, CLASSDROPDOWN);
            $data['userLink'] = form_dropdown_disabled('userLink', $users[0], $ticketRule->UserIdLink, CLASSDROPDOWN);
            $data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), $ticketRule->NatureOfWorkId, CLASSDROPDOWN);
            $data['contactMoment'] = form_dropdown('contactmoment', getContactMomentDropdown($businessId), $ticketRule->ContactMomentId, CLASSDROPDOWN);
            $data['business'] = $business;
            $data['projects'] = $this->Project_model->getAll($customer->Id, $businessId)->result();
            if ($ticketProject != null) {
                $data['ticketProject'] = $ticketProject;
                $data['projectPhases'] = $this->Project_model->getPhases($ticketProject->Id, $businessId)->result();
            }


            $this->load->view('work/update', $data);
        }
    }

    public function progress() {
        if (!isLogged()) {
            redirect('login');
        }

        $ticketId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

        if ($ticket == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
        if ($statusses == NULL) {
            $this->session->set_tempdata('err_message', 'Er zijn geen statussen voor de tickets. Maak eerst een status aan in de instellingen voordat je een ticket toevoegt', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $ticketRules = $this->Tickets_model->getTicketRules($ticketId, $businessId)->result();

        $customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();

        $data['ticket'] = $ticket;
        $data['ticketRules'] = $ticketRules;
        $data['customer'] = $customer;

        $this->load->view('work/progress', $data);
    }

    public function product() {
        if (!isLogged()) {
            redirect('login');
        }

        $ticketId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();

        if ($ticket == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->load->helper('Business');
            $this->db->trans_start();
            $a = array();

            $this->Tickets_productmodel->removeAll($ticketId, $businessId);

            foreach ($_POST['number'] as $value) {
                $articlenumber = $_POST['articlenumber' . $value];
                $eanCode = $_POST['ean_code'.$value];
                $articledescription = $_POST['articledescription' . $value];
                $amount = $_POST['amount' . $value];

                $salesprice = $_POST['salesprice' . $value];
                $discount = $_POST['discount' . $value];


                $tax = $_POST['tax' . $value];
                $total = $_POST['total' . $value];


                $dataRule = array(
                    'TicketId' => $ticket->Id,
                    'ArticleC' => $articlenumber,
                    'EanCode' => $eanCode,
                    'Amount' => $amount,
                    'Description' => $articledescription,
                    'Price' => $salesprice,
                    'Discount' => $discount,
                    'Tax' => $tax,
                    'Total' => $total,
                    'CustomerId' => $ticket->CustomerId,
                    'BusinessId' => $businessId
                );


                $ticketRuleId = $this->Tickets_productmodel->insertRule($dataRule);

                $a[] = $ticketRuleId;
            }

            $this->db->trans_complete();

            $this->session->set_tempdata('err_message', 'Producten succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect('work/product/' . $ticket->Id);
        } else {

            $customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();
            $ticketProducts = $this->Tickets_productmodel->getProducts($ticketId, $businessId)->result();

            if ($this->session->tempdata('err_message')) {
                $data['tpl_msg'] = $this->session->tempdata('err_message');
                $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
                $this->session->unset_tempdata('err_message');
                $this->session->unset_tempdata('err_messagetype');
            }

            $data['customer'] = $customer;
            $data['ticketProducts'] = $ticketProducts;

            $this->load->view('work/product', $data);
        }
    }

    public function updateticketrule() {
        if (!isLogged()) {
            redirect('login');
        }

        $progressId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;
        $business = getBusiness($businessId);

        if ($business->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
        if ($statusses == NULL) {
            $this->session->set_tempdata('err_message', 'Er zijn geen statussen voor de tickets. Maak eerst een status aan in de instellingen voordat je een ticket toevoegt', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $ticketRule = $this->Tickets_model->getProgress($progressId, $businessId)->row();

        $ticketBase = $this->Tickets_model->getBase($ticketRule->TicketId)->row();

        if ($ticketRule == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->db->trans_start();

            $data = array(
                'UserId' => $_POST['user'],
                'UserIdLink' => $_POST['userLink'],
                'ContactId' => $_POST['contactId'],
                'ContactMomentId' => $_POST['contactmoment'],
                'Date' => strtotime($_POST['date']),
                'Status' => $_POST['status'],
                'StartWork' => strtotime($_POST['startwork']),
                'EndWork' => strtotime($_POST['endwork']),
                'TotalWork' => $_POST['totalWork'],
                'NatureOfWorkId' => $_POST['natureofwork'],
                'ActionUser' => $_POST['actionUser'],
                'InternalNote' => $_POST['internalNote']
            );

            $this->Tickets_model->updateTicketR($data, $ticketRule->Id);

            $ticket = $this->Tickets_model->getTicket($ticketRule->TicketId, $business->Id)->row();

            $this->_Attachments_upload($_FILES, $ticket, $ticketRule->Id);

            $latestTicketRule = getLastTicketRule($ticket->Id);

            $dataT = array(
                'LatestTicketRule' => $latestTicketRule->Id
            );
            $this->Tickets_model->updateTicket($ticket->Id, $dataT);


            $this->db->trans_complete();

            $this->session->set_tempdata('err_message', 'Statusupdate succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);


            $mailResult = true;
            if ($business->WorkEmail) {
                if ($data['UserId'] != $data['UserIdLink'] && $ticketRule->UserIdLink != $data['UserIdLink']) {
                    // Verstuur e-mail.

                    $this->load->helper('mail');
                    $mailResult = sendNewTicket(getAccountMail($data['UserIdLink']), getAccountName($data['UserIdLink']), $ticketRule->TicketId, $progressId);
                }
            }

            $this->session->set_tempdata('err_message', 'Statusupdate succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect('work/progress/' . $ticketRule->TicketId);
        } else {
            $customer = $this->Customers_model->getCustomer($ticketRule->CustomerId, $businessId)->row();

            $users = getUserDropdown($businessId);
            $contacts = getContactDropdown($customer->Id, $businessId);

            $data['ticketbase'] = $ticketBase;
            $data['ticketrule'] = $ticketRule;
            $data['customer'] = $customer;
            $data['user'] = form_dropdown_disabled('user', $users[0], $ticketRule->UserId, CLASSDROPDOWN, $users[1]);
            $data['contact'] = form_dropdown_disabled('contactId', $contacts[0], $ticketRule->ContactId, CLASSDROPDOWN, $contacts[1]);
            $data['userLink'] = form_dropdown_disabled('userLink', $users[0], $ticketRule->UserIdLink, CLASSDROPDOWN, $users[1]);
            $data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), $ticketRule->NatureOfWorkId, CLASSDROPDOWN);
            $data['contactMoment'] = form_dropdown('contactmoment', getContactMomentDropdown($businessId), $ticketRule->ContactMomentId, CLASSDROPDOWN);
            $data['status'] = form_dropdown('status', getStatusDropdown($businessId), $ticketRule->Status, CLASSDROPDOWN);
            $data['attachments'] = $this->Attachments_model->getTicketRuleAttachments($ticketRule->Id, $businessId)->result();
            $data['business'] = $business;

            $this->load->view('work/updateticketrule', $data);
        }
    }

    public function createticketrule() {
        if (!isLogged()) {
            redirect('login');
        }

        $ticketId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;
        $userId = $this->session->userdata('user')->Id;

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $statusses = $this->Tickets_statusmodel->getAll($businessId)->result();
        if ($statusses == NULL) {
            $this->session->set_tempdata('err_message', 'Er zijn geen statussen voor de tickets. Maak eerst een status aan in de instellingen voordat je een ticket toevoegt', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $ticket = $this->Tickets_model->getTicket($ticketId, $businessId)->row();
        $business = $this->Business_model->getBusiness($businessId)->row();

        if ($ticket == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $this->db->trans_start();

            $data = array(
                'TicketId' => $ticketId,
                'Number' => $ticket->Number,
                'UserId' => $_POST['user'],
                'UserIdLink' => $_POST['userLink'],
                'ContactId' => $_POST['contactId'],
                'ContactMomentId' => $_POST['contactmoment'],
                'Date' => strtotime($_POST['date']),
                'Status' => $_POST['status'],
                'StartWork' => strtotime($_POST['startwork']),
                'EndWork' => strtotime($_POST['endwork']),
                'TotalWork' => $_POST['totalWork'],
                'NatureOfWorkId' => $_POST['natureofwork'],
                'ActionUser' => $_POST['actionUser'],
                'InternalNote' => $_POST['internalNote'],
                'CustomerId' => $ticket->CustomerId,
                'BusinessId' => $businessId
            );

            $ticketRuleId = $this->Tickets_model->createTicketR($data);

            $this->_Attachments_upload($_FILES, $ticket, $ticketRuleId);

            $latestTicketRule = getLastTicketRule($ticketId);

            $dataT = array(
                'LatestTicketRule' => $latestTicketRule->Id
            );
            $this->Tickets_model->updateTicket($ticketId, $dataT);

            $this->db->trans_complete();

            $mailResult = true;
            if ($business->WorkEmail) {
                if ($data['UserId'] != $data['UserIdLink'] && $userId != $data['UserIdLink']) {
                    // Verstuur e-mail.

                    $this->load->helper('mail');
                    $mailResult = sendNewTicket(getAccountMail($data['UserIdLink']), getAccountName($data['UserIdLink']), $ticketId, $ticketRuleId);
                }
            }

            if ($mailResult == false) {
                // ERROR
            } else {
                $this->session->set_tempdata('err_message', 'Statusupdate succesvol toegevoegd', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
                redirect('work/progress/' . $ticketId);
            }
        } else {

            $ticketRule = getLastTicketRule($ticketId);
            $ticketBase = $this->Tickets_model->getBase($ticketRule->TicketId)->row();

            $customer = $this->Customers_model->getCustomer($ticket->CustomerId, $businessId)->row();

            $users = getUserDropdown($businessId);
            $contacts = getContactDropdown($customer->Id, $businessId);
            $data['user'] = form_dropdown_disabled('user', $users[0], $userId, CLASSDROPDOWN, $users[1]);
            $data['contact'] = form_dropdown_disabled('contactId', $contacts[0], $ticketRule->ContactId, CLASSDROPDOWN, $contacts[1]);
            $data['userLink'] = form_dropdown_disabled('userLink', $users[0], $userId, CLASSDROPDOWN, $users[1]);
            $data['natureOfWork'] = form_dropdown('natureofwork', getNatureOfWorkDropdown($businessId), $ticketRule->NatureOfWorkId, CLASSDROPDOWN);
            $data['contactMoment'] = form_dropdown('contactmoment', getContactMomentDropdown($businessId), $ticketRule->ContactMomentId, CLASSDROPDOWN);
            $data['status'] = form_dropdown('status', getStatusDropdown($businessId), $ticketRule->Status, CLASSDROPDOWN);
            $data['ticketbase'] = $ticketBase;
            $data['attachments'] = array();

            $data['ticketrule'] = (object) array(
                        'InternalNote' => '',
                        'ActionUser' => '',
                        'Date' => strtotime(date('d-m-Y')),
                        'StartWork' => strtotime(date('H:i')),
                        'EndWork' => strtotime(date('H:i'))
            );

            $this->load->view('work/updateticketrule', $data);
        }
    }

    public function removeticketrule() {
        if (!isLogged()) {
            redirect('login');
        }

        $progressId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $ticketRule = $this->Tickets_model->getProgress($progressId, $businessId)->row();


        if ($ticketRule == null) {
            $this->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $this->db->trans_start();

        $this->Tickets_model->removeTicketRule($progressId, $ticketRule->TicketId, $businessId);

        $latestTicketRule = getLastTicketRule($ticketRule->TicketId);

        $dataT = array(
            'LatestTicketRule' => $latestTicketRule->Id
        );
        $this->Tickets_model->updateTicket($ticketRule->TicketId, $dataT);

        $this->db->trans_complete();

        $this->session->set_tempdata('err_message', 'Statusupdate succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('work/progress/' . $ticketRule->TicketId);
    }

    public function getProducts()
    {
        if ($this->input->is_ajax_request()){
            $businessId = $this->session->userdata('user')->BusinessId;

            if ($this->input->post('articleNumber')) {
                $searchValue = $this->input->post('articleNumber');
                $data['products'] = $this->Product_model->getProductsByArticleNumber($businessId, $searchValue)->result();
                echo json_encode($data);
            }
            elseif ($this->input->post('articleDescription')) {
                $searchValue = $this->input->post('articleDescription');
                $data['products'] = $this->Product_model->getProductsByArticleDescription($businessId, $searchValue)->result();
                echo json_encode($data);
            }
        }
        else{
            show_404();
        }
    }

    /**
     * Global function for uploading ticket attachments.
     * @param array $files $_FILES.
     * @param object $ticket Ticket data.
     * @param int $ticketRuleId ticketrule id (optional).
     */
    private function _Attachments_upload($files, $ticket, $ticketRuleId = 0)
    {

        $businessId = $this->session->userdata('user')->BusinessId;
        $business = $this->Business_model->getBusiness($businessId)->row();

        if (!empty($files))
        {
            $path = "";
            $dirName = "./uploads/$business->DirectoryPrefix/tickets/T$ticket->Id/";

            foreach ($files['upload']['name'] as $key => $upload)
            {
                $naam = $files['upload']['name'][$key];
                $error = $files['upload']['error'][$key];

                if ($error != 4)
                {
                    if (!empty($_POST['FileDescription'][$key]) && isset($_POST['FileDescription'][$key]))
                    {
                        $displayName = $_POST['FileDescription'][$key];
                    }

                    else
                    {
                        $displayName = $naam;
                    }

                    if ($error == 1 || $error == 2 || $error == 3 || $error == 5 || $error == 6 || $error == 7 || $error == 8)
                    {
                        $fileError[] = $key;
                        continue;
                    }

                    $path = $dirName . $naam;

                    // Mapje voor ticketID bestaat nog niet, aanmaken!
                    if (!file_exists($dirName)) {
                        mkdir($dirName, 0777, true);
                    }

                    move_uploaded_file($files["upload"]["tmp_name"][$key], $path);


                    $fileData = array(
                        'TicketId' => $ticket->Id,
                        'TicketRuleId' => $ticketRuleId,
                        'DisplayName' => $displayName,
                        'Name' => $files['upload']['name'][$key],
                        'CustomerId' => $ticket->CustomerId,
                        'BusinessId' => $ticket->BusinessId,
                    );

                    $this->Attachments_model->addAttachment($fileData);
                }
            }
        }
    }

}
