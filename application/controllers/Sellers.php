<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sellers extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('sellers/Sellers_model', '', TRUE);
        $this->load->model('transporter/Transporter_model', '', TRUE);
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }

        $data['sellers'] = $this->Sellers_model->getAll($this->session->userdata('user')->BusinessId)->result();

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $this->load->view('sellers/default', $data);
    }

    public function create() {
        if (!isLogged()) {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;
        $data['readonly'] = '';

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = array(
                'Name' => $_POST['name'],
                'Client_id' => $_POST['ClientId'],
                'Street' => $_POST['streetname'],
                'House_number' => $_POST['streetnumber'],
                'Number_addition' => $_POST['streetaddition'],
                'Zip_code' => $_POST['zipcode'],
                'City' => $_POST['city'],
                'Country' => $_POST['country'],
                'Default_transport' => $_POST['transport'],
                'Only_option' => $_POST['onlyOption'] ? 1 : 0,
                'Phone' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Mail' => $_POST['email'],
                'Website' => $_POST['website'],
                'Facebook' => $_POST['facebook'],
                'Twitter' => $_POST['twitter'],
                'Import' => $_POST['import'],
                'BusinessId' => $businessId
            );

            $this->Sellers_model->createSeller($data);

            $this->session->set_tempdata('err_message', 'Verkoopkanaal succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("sellers");
        } else {
            
            // Get all sellers with assigned imports.
            $sellersWithImport = $this->Sellers_model->getWithImport($businessId)->result();
            $assignedImports = array();
            foreach ($sellersWithImport as $sellerWithImport) {
                $assignedImports[] = $sellerWithImport->Import;
            }

            $seller = (object) array(
                'Name' => '',
                'Client_id' => '',
                'Street' => '',
                'House_number' => '',
                'Number_addition' => '',
                'Zip_code' => '',
                'City' => '',
                'Country' => '',
                'Default_transport' => '',
                'Only_option' => '',
                'Phone' => '',
                'Fax' => '',
                'Mail' => '',
                'Website' => '',
                'Facebook' => '',
                'Twitter' => '',
                'Import' => ''
            );

            $data['seller'] = $seller;
            $data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
            $data['assignedImports'] = $assignedImports;

            $this->load->view('sellers/edit', $data);
        }
    }

    public function edit() {
        if (!isLogged()) {
            redirect('login');
        }

        $Seller_id = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if ($this->session->userdata('user')->CustomerManagement != 1) {
            $data['readonly'] = 'readonly';
        } else {
            $data['readonly'] = '';
        }

        $seller = $this->Sellers_model->getSeller($Seller_id, $businessId)->row();

        if ($seller == null) {

            $this->session->set_tempdata('err_message', 'Dit verkoopkanaal bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            
            $data = array(
                'Name' => $_POST['name'],
                'Client_id' => $_POST['ClientId'],
                'Street' => $_POST['streetname'],
                'House_number' => $_POST['streetnumber'],
                'Number_addition' => $_POST['streetaddition'],
                'Zip_code' => $_POST['zipcode'],
                'City' => $_POST['city'],
                'Country' => $_POST['country'],
                'Default_transport' => $_POST['transport'],
                'Only_option' => $_POST['onlyOption'],
                'Phone' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Mail' => $_POST['email'],
                'Website' => $_POST['website'],
                'Facebook' => $_POST['facebook'],
                'Twitter' => $_POST['twitter'],
                'Import' => $_POST['import']
            );

            $this->Sellers_model->updateSeller($Seller_id, $data);

            $this->session->set_tempdata('err_message', 'verkoopkanaal succesvol geupdate', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("sellers");
        } else {
            
            // Get all transporters with assigned imports.
            $sellersWithImport = $this->Sellers_model->getWithImport($businessId, $seller->Import)->result();
            $assignedImports = array();
            foreach ($sellersWithImport as $sellerWithImport) {
                $assignedImports[] = $sellerWithImport->Import;
            }

            $data['seller'] = $seller;
            $data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();
            $data['assignedImports'] = $assignedImports;

            $this->load->view('sellers/edit', $data);
        }
    }

    public function search() {
        if (!isLogged()) {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;

        $data['suppliers'] = $this->Supplier_model->getAll($this->session->userdata('user')->BusinessId)->result();

        $this->load->view('supplier/search', $data);
    }

}
