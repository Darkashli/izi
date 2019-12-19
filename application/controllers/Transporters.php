<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transporters extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('transporter/Transporter_model', '', TRUE);
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }

        $data['transporters'] = $this->Transporter_model->getAll($this->session->userdata('user')->BusinessId)->result();

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $this->load->view('transporters/default', $data);
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
                'Phone' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Mail' => $_POST['email'],
                'Website' => $_POST['website'],
                'Facebook' => $_POST['facebook'],
                'Twitter' => $_POST['twitter'],
                'BusinessId' => $businessId
            );

            $transporterId = $this->Transporter_model->createTransporter($data);
            
            foreach ($_POST['imports'] as $import) {
                $data = array(
                    'TransporterId' => $transporterId,
                    'Import' => $import
                );
                $this->Transporter_model->createTransporterImport($data);
            }

            $this->session->set_tempdata('err_message', 'Vervoerder succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("transporters");
        } else {

            $transporter = (object) array(
                'Name' => '',
                'Client_id' => '',
                'Street' => '',
                'House_number' => '',
                'Number_addition' => '',
                'Zip_code' => '',
                'City' => '',
                'Country' => '',
                'Phone' => '',
                'Fax' => '',
                'Mail' => '',
                'Website' => '',
                'Facebook' => '',
                'Twitter' => '',
                'Import' => '',
                'BusinessId' => ''
            );
            
            // Get all transporters with assigned imports.
            $assignedImports = $this->Transporter_model->getAllImport($businessId)->result();
            $assignedImportIds = array();
            foreach ($assignedImports as $assignedImport) {
                $assignedImportIds[] = $assignedImport->Import;
            }

            $data['transporter'] = $transporter;
            $data['transporterImportNames'] = array();
            $data['assignedImportIds'] = $assignedImportIds;

            $this->load->view('transporters/edit', $data);
        }
    }


    public function edit() {
        if (!isLogged()) {
            redirect('login');
        }

        $Transporter_id = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        if ($this->session->userdata('user')->CustomerManagement != 1) {
            $data['readonly'] = 'readonly';
        } else {
            $data['readonly'] = '';
        }

        $transporter = $this->Transporter_model->getTransporter($Transporter_id, $businessId)->row();

        if ($transporter == null) {
            $this->session->set_tempdata('err_message', 'Deze vervoerder bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("transporters");
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
                'Phone' => $_POST['phonenumber'],
                'Fax' => $_POST['fax'],
                'Mail' => $_POST['email'],
                'Website' => $_POST['website'],
                'Facebook' => $_POST['facebook'],
                'Twitter' => $_POST['twitter']
            );
            $this->Transporter_model->updateTransporter($Transporter_id, $data);
            
            $this->Transporter_model->deleteTransporterImports($Transporter_id);
            foreach ($_POST['imports'] as $import) {
                $data = array(
                    'TransporterId' => $Transporter_id,
                    'Import' => $import
                );
                $this->Transporter_model->createTransporterImport($data);
            }

            $this->session->set_tempdata('err_message', 'Vervoerder succesvol geupdate', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("transporters");
        } else {
            
            // Get all transporters with assigned imports.
            $assignedImports = $this->Transporter_model->getAllImport($businessId, array($transporter->Transporter_id))->result();
            $assignedImportIds = array();
            foreach ($assignedImports as $assignedImport) {
                $assignedImportIds[] = $assignedImport->Import;
            }
            
            $transporterImports = $this->Transporter_model->getTransporterImport($transporter->Transporter_id)->result();
            
            $data['transporter'] = $transporter;
            $data['assignedImportIds'] = $assignedImportIds;
            $data['transporterImportNames'] = array();
            
            if (!empty($transporterImports)) {
                foreach ($transporterImports as $transporterImport) {
                    $data['transporterImportNames'][] = $transporterImport->Import;
                }
            }
            
            $this->load->view('transporters/edit', $data);
        }
    }
}
