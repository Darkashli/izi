<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SystemManagement extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('systemmanagement');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('systemmanagement/SystemManagement_model', '', TRUE);
        $this->load->model('customers/Customers_model', '', TRUE);
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        $systemmanagement = $this->SystemManagement_model->getSystemManagementNetworkInformation($customerId)->row();

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            if ($systemmanagement == null) {
                // Maak nieuwe aan

                $data = array(
                    'IpRange' => $_POST['iprange'],
                    'SubnetMasker' => $_POST['subnetmasker'],
                    'DefaultGateway' => $_POST['defaultgateway'],
                    'PrimaryDns' => $_POST['primarydns'],
                    'SecondaryDns' => $_POST['secondarydns'],
                    'DnsForward1' => $_POST['dnsforward1'],
                    'DnsForward2' => $_POST['dnsforward2'],
                    'SmtpServer1' => $_POST['smtpserver1'],
                    'SmtpServer2' => $_POST['smtpserver2'],
                    'DhcpRange10' => $_POST['dhcprange10'],
                    'DhcpRange11' => $_POST['dhcprange11'],
                    'DhcpRange20' => $_POST['dhcprange20'],
                    'DhcpRange21' => $_POST['dhcprange21'],
                    'Note' => $_POST['note'],
                    'CustomerId' => $customerId
                );


                $systemmanagementId = $this->SystemManagement_model->createSystemManagementNetworkInformation($data);

                $this->session->set_tempdata('err_message', 'Gegevens zijn succesvol opgeslagen', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
                redirect('SystemManagement/index/' . $customerId);
            } else {
                // Pas de huidige aan

                $data = array(
                    'IpRange' => $_POST['iprange'],
                    'SubnetMasker' => $_POST['subnetmasker'],
                    'DefaultGateway' => $_POST['defaultgateway'],
                    'PrimaryDns' => $_POST['primarydns'],
                    'SecondaryDns' => $_POST['secondarydns'],
                    'DnsForward1' => $_POST['dnsforward1'],
                    'DnsForward2' => $_POST['dnsforward2'],
                    'SmtpServer1' => $_POST['smtpserver1'],
                    'SmtpServer2' => $_POST['smtpserver2'],
                    'DhcpRange10' => $_POST['dhcprange10'],
                    'DhcpRange11' => $_POST['dhcprange11'],
                    'DhcpRange20' => $_POST['dhcprange20'],
                    'DhcpRange21' => $_POST['dhcprange21'],
                    'Note' => $_POST['note']
                );

                $this->SystemManagement_model->updateSystemManagementNetworkInformation($systemmanagement->Id, $data);

                $this->session->set_tempdata('err_message', 'Gegevens zijn succesvol opgeslagen', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
                redirect('SystemManagement/index/' . $customer->Id);
            }
        } else {

            if ($systemmanagement == null) {
                $systemmanagement = (object) array(
                            'IpRange' => '',
                            'SubnetMasker' => '',
                            'DefaultGateway' => '',
                            'PrimaryDns' => '',
                            'SecondaryDns' => '',
                            'DnsForward1' => '',
                            'DnsForward2' => '',
                            'SmtpServer1' => '',
                            'SmtpServer2' => '',
                            'DhcpRange10' => '',
                            'DhcpRange11' => '',
                            'DhcpRange20' => '',
                            'DhcpRange21' => '',
                            'Note' => ''
                );
            }


            if ($this->session->tempdata('err_message')) {
                $data['tpl_msg'] = $this->session->tempdata('err_message');
                $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
                $this->session->unset_tempdata('err_message');
                $this->session->unset_tempdata('err_messagetype');
            }

            $data['systemmanagement'] = $systemmanagement;

            $this->load->view('systemmanagement/default', $data);
        }
    }

    public function internetData() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        $internetData = $this->SystemManagement_model->getInternetData($customerId)->row();

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $ipAddresses = array();

            foreach ($_POST['number'] as $value) {
                $ipaddress = $_POST['ipaddress' . $value];
                $ipAddresses[$value] = $ipaddress;
            }

            if ($internetData == null) {
                // Maak nieuwe aan
                $data = array(
                    'Provider' => $_POST['provider'],
                    'Type' => $_POST['type'],
                    'IpRange' => $_POST['iprange'],
                    'SubnetMasker' => $_POST['subnetmasker'],
                    'DefaultGateway' => $_POST['defaultgateway'],
                    'PrimaryDns' => $_POST['primarydns'],
                    'SecondaryDns' => $_POST['secondarydns'],
                    'IpAddress' => serialize($ipAddresses),
                    'Username' => $_POST['username'],
                    'Password' => $_POST['password'],
                    'VPI' => $_POST['vpi'],
                    'Speed' => $_POST['speed'],
                    'Note' => $_POST['note'],
                    'CustomerId' => $customerId
                );


                $internetDataId = $this->SystemManagement_model->createInternetData($data);

                $this->session->set_tempdata('err_message', 'Gegevens zijn succesvol opgeslagen', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
                redirect('SystemManagement/internetData/' . $customerId);
            } else {
                // Pas de huidige aan

                $data = array(
                    'Provider' => $_POST['provider'],
                    'Type' => $_POST['type'],
                    'IpRange' => $_POST['iprange'],
                    'SubnetMasker' => $_POST['subnetmasker'],
                    'DefaultGateway' => $_POST['defaultgateway'],
                    'PrimaryDns' => $_POST['primarydns'],
                    'SecondaryDns' => $_POST['secondarydns'],
                    'IpAddress' => serialize($ipAddresses),
                    'Username' => $_POST['username'],
                    'Password' => $_POST['password'],
                    'VPI' => $_POST['vpi'],
                    'Speed' => $_POST['speed'],
                    'Note' => $_POST['note']
                );

                $this->SystemManagement_model->updateInternetData($internetData->Id, $data);

                $this->session->set_tempdata('err_message', 'Gegevens zijn succesvol opgeslagen', 300);
                $this->session->set_tempdata('err_messagetype', 'success', 300);
                redirect('SystemManagement/internetData/' . $customer->Id);
            }
        } else {

            if ($internetData == null) {
                $internetData = (object) array(
                            'Provider' => '',
                            'Type' => '',
                            'IpRange' => '',
                            'SubnetMasker' => '',
                            'DefaultGateway' => '',
                            'PrimaryDns' => '',
                            'SecondaryDns' => '',
                            'IpAddress' => '',
                            'Username' => '',
                            'Password' => '',
                            'VPI' => '',
                            'Speed' => '',
                            'Note' => ''
                );
            }


            if ($this->session->tempdata('err_message')) {
                $data['tpl_msg'] = $this->session->tempdata('err_message');
                $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
                $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

                $this->session->unset_tempdata('err_message');
                $this->session->unset_tempdata('err_messagetype');
                $this->session->unset_tempdata('err_timeout');
            }

            $data['internetdata'] = $internetData;

            $this->load->view('systemmanagement/internetData', $data);
        }
    }

    public function hardware() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['hardwares'] = $this->SystemManagement_model->getAllHardware($customerId)->result();

        $this->load->view('systemmanagement/hardware', $data);
    }

    public function createHardware() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $hardDisks = array();

            foreach ($_POST['number'] as $value) {
                $harddisk = $_POST['harddisk' . $value];
                $hardDisks[$value] = $harddisk;
            }

            $data = array(
                'Kind' => $_POST['kind'],
                'Brand' => $_POST['brand'],
                'Type' => $_POST['type'],
                'Processor' => $_POST['processor'],
                'Memory' => $_POST['memory'],
                'OperatingSystem' => $_POST['operatingsystem'],
                'HardDisks' => serialize($hardDisks),
                'MacAddress1' => $_POST['macaddress1'],
                'MacAddress2' => $_POST['macaddress2'],
                'SerialNumber' => $_POST['serialnumber'],
                'Hostname' => $_POST['hostname'],
                'IpAddress' => $_POST['ipaddress'],
                'Comments' => $_POST['comments'],
                'CustomerId' => $customerId
            );

            $this->SystemManagement_model->createHardware($data);

            $this->session->set_tempdata('err_message', 'Apparaat succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/hardware/$customerId");
        } else {
            $data['hardware'] = (object) array(
                        'Brand' => '',
                        'Type' => '',
                        'Processor' => '',
                        'Memory' => '',
                        'OperatingSystem' => '',
                        'HardDisks' => '',
                        'MacAddress1' => '',
                        'MacAddress2' => '',
                        'SerialNumber' => '',
                        'Hostname' => '',
                        'IpAddress' => '',
                        'Comments' => ''
            );

            $data['hardwareKind'] = form_dropdown('kind', unserialize(HARDWAREKIND), '', CLASSDROPDOWN . ' onclick="change()" id="kind"');

            $this->load->view('systemmanagement/updateHardware', $data);
        }
    }

    public function updateHardware() {
        if (!isLogged()) {
            redirect('login');
        }

        $hardwareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $hardware = $this->SystemManagement_model->getHardware($hardwareId)->row();

        if ($hardware == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $hardDisks = array();

            foreach ($_POST['number'] as $value) {
                $harddisk = $_POST['harddisk' . $value];
                $hardDisks[$value] = $harddisk;
            }

            $data = array(
                'Kind' => $_POST['kind'],
                'Brand' => $_POST['brand'],
                'Type' => $_POST['type'],
                'Processor' => $_POST['processor'],
                'Memory' => $_POST['memory'],
                'OperatingSystem' => $_POST['operatingsystem'],
                'HardDisks' => serialize($hardDisks),
                'MacAddress1' => $_POST['macaddress1'],
                'MacAddress2' => $_POST['macaddress2'],
                'SerialNumber' => $_POST['serialnumber'],
                'Hostname' => $_POST['hostname'],
                'IpAddress' => $_POST['ipaddress'],
                'Comments' => $_POST['comments']
            );

            $this->SystemManagement_model->updateHardware($hardwareId, $data);

            $this->session->set_tempdata('err_message', 'Apparaat is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/hardware/" . $hardware->CustomerId);
        } else {
            $data['hardware'] = $hardware;

            $data['hardwareKind'] = form_dropdown('kind', unserialize(HARDWAREKIND), $hardware->Kind, CLASSDROPDOWN . ' onclick="change()" id="kind"');

            $this->load->view('systemmanagement/updateHardware', $data);
        }
    }

    public function deleteHardware() {
        if (!isLogged()) {
            redirect('login');
        }

        $hardwareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $hardware = $this->SystemManagement_model->getHardware($hardwareId)->row();

        if ($hardware == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->session->set_tempdata('err_message', 'Weet u zeker dat u dit apparaat wilt verwijderen? Klik <a href="' . base_url() . 'SystemManagement/deleteHardwareConfirm/' . $hardwareId . '">hier</a> om dit te bevestigen.', 300);
        $this->session->set_tempdata('err_messagetype', 'warning', 300);
        $this->session->set_tempdata('err_timeout', 0, 300);
        redirect('SystemManagement/hardware/' . $hardware->CustomerId);
    }

    public function deleteHardwareConfirm() {
        if (!isLogged()) {
            redirect('login');
        }

        $hardwareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $hardware = $this->SystemManagement_model->getHardware($hardwareId)->row();

        if ($hardware == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteHardware($hardware->Id);

        $this->session->set_tempdata('err_message', 'Hardware succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/hardware/' . $hardware->CustomerId);
    }

    public function software() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['softwares'] = $this->SystemManagement_model->getAllSoftware($customerId)->result();

        $this->load->view('systemmanagement/software', $data);
    }

    public function createSoftware() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Developer' => $_POST['developer'],
                'ProductName' => $_POST['productname'],
                'KindSoftware' => $_POST['kindsoftware'],
                'LicenseNumber' => $_POST['licensenumber'],
                'SupplierName' => $_POST['suppliername'],
                'SupplierPhoneNumber' => $_POST['supplierphonenumber'],
                'SupplierWebsite' => $_POST['supplierwebsite'],
                'Comments' => $_POST['comments'],
                'CustomerId' => $customerId
            );

            $this->SystemManagement_model->createSoftware($data);
            $this->session->set_tempdata('err_message', 'Programma succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/software/$customerId");
        } else {

            $data['software'] = (object) array(
                        'Developer' => '',
                        'ProductName' => '',
                        'KindSoftware' => '',
                        'LicenseNumber' => '',
                        'SupplierName' => '',
                        'SupplierPhonenumber' => '',
                        'SupplierWebsite' => '',
                        'Comments' => ''
            );

            $this->load->view('systemmanagement/updateSoftware', $data);
        }
    }

    public function updateSoftware() {
        if (!isLogged()) {
            redirect('login');
        }

        $softwareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $software = $this->SystemManagement_model->getSoftware($softwareId)->row();

        if ($software == null) {
            $this->session->set_tempdata('err_message', 'Deze software bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Developer' => $_POST['developer'],
                'ProductName' => $_POST['productname'],
                'KindSoftware' => $_POST['kindsoftware'],
                'LicenseNumber' => $_POST['licensenumber'],
                'SupplierName' => $_POST['suppliername'],
                'SupplierPhoneNumber' => $_POST['supplierphonenumber'],
                'SupplierWebsite' => $_POST['supplierwebsite'],
                'Comments' => $_POST['comments']
            );

            $this->SystemManagement_model->updateSoftware($software->Id, $data);

            $this->session->set_tempdata('err_message', 'Programma is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/software/" . $software->CustomerId);

        } else {
            $data['software'] = $software;

            $this->load->view('systemmanagement/updateSoftware', $data);
        }
    }

    public function deleteSoftware() {
        if (!isLogged()) {
            redirect('login');
        }

        $softwareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $software = $this->SystemManagement_model->getSoftware($softwareId)->row();

        if ($software == null) {
            $this->session->set_tempdata('err_message', 'Deze software bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteSoftware($software->Id);

        $this->session->set_tempdata('err_message', 'Software succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/software/' . $software->CustomerId);
    }

    public function group() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['groups'] = $this->SystemManagement_model->getAllGroup($customerId)->result();

        $this->load->view('systemmanagement/group', $data);
    }

    public function createGroup() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Name' => $_POST['name'],
                'Type' => $_POST['type'],
                'Comments' => $_POST['comments'],
                'Password' => $_POST['password'],
                'CustomerId' => $customerId
            );

            $this->SystemManagement_model->createGroup($data);
            $this->session->set_tempdata('err_message', 'Groep succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/group/$customerId");
        } else {
            $data['group'] = (object) array(
                        'Name' => '',
                        'Type' => '',
                        'Comments' => '',
                        'Password' => ''
            );

            $this->load->view('systemmanagement/updateGroup', $data);
        }
    }

    public function updateGroup() {
        if (!isLogged()) {
            redirect('login');
        }

        $groupId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $group = $this->SystemManagement_model->getGroup($groupId)->row();

        if ($group == null) {
            $this->session->set_tempdata('err_message', 'Deze groep bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data = array(
                'Name' => $_POST['name'],
                'Type' => $_POST['type'],
                'Password' => $_POST['password'],
                'Comments' => $_POST['comments']
            );

            $this->SystemManagement_model->updateGroup($group->Id, $data);

            $this->session->set_tempdata('err_message', 'Groep is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/group/" . $group->CustomerId);
        } else {
            $data['group'] = $group;
            $this->load->view('systemmanagement/updateGroup', $data);
        }
    }

    public function deleteGroup() {
        if (!isLogged()) {
            redirect('login');
        }

        $groupId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $group = $this->SystemManagement_model->getGroup($groupId)->row();

        if ($group == null) {
            $this->session->set_tempdata('err_message', 'Deze groep bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteGroup($group->Id);

        $this->session->set_tempdata('err_message', 'Groep succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/group/' . $group->CustomerId);
    }

    public function user() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['users'] = $this->SystemManagement_model->getAllUser($customerId)->result();

        $this->load->view('systemmanagement/user', $data);
    }

    public function createUser() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        $group = $this->SystemManagement_model->getAllGroup($customerId)->result();
        $users = $this->SystemManagement_model->getAllUser($customerId)->result();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $selectedGroups = array();

            foreach ($_POST['groups'] as $key => $value) {
                array_push($selectedGroups, $value);
            }

            $selectedUsers = array();

            foreach ($_POST['users'] as $key => $value) {
                array_push($selectedUsers, $value);
            }


            $data = array(
                'FirstName' => $_POST['firstname'],
                'Insertion' => $_POST['insertion'],
                'LastName' => $_POST['lastname'],
                'Email' => $_POST['email'],
                'Username' => $_POST['username'],
                'Password' => $_POST['password'],
                'ComputerName' => $_POST['computername'],
                'Groups' => serialize($selectedGroups),
                'Users' => serialize($selectedUsers),
                'Comments' => $_POST['comments'],
                'ExchangeUsername' => $_POST['exchangeusername'],
                'ExchangePassword' => $_POST['exchangepassword'],
                'CustomerId' => $customerId
            );

            $this->SystemManagement_model->createUser($data);
            $this->session->set_tempdata('err_message', 'Gebruiker succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/user/$customerId");
        } else {

            $data['user'] = (object) array(
                        'FirstName' => '',
                        'Insertion' => '',
                        'LastName' => '',
                        'Email' => '',
                        'Username' => '',
                        'Password' => '',
                        'ComputerName' => '',
                        'Comments' => '',
                        'ExchangeUsername' => '',
                        'ExchangePassword' => ''
            );
            // $data['groups'] = form_dropdown('groups[]', systemManagementUserDropdown($customerId), '', CLASSDROPDOWN . ' multiple="multiple" id="groups"');
            $data['groups'] = $group;
            $data['users'] = $users;

            $this->load->view('systemmanagement/updateUser', $data);
        }
    }

    public function updateUser() {
        if (!isLogged()) {
            redirect('login');
        }

        $userId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $user = $this->SystemManagement_model->getUser($userId)->row();

        $group = $this->SystemManagement_model->getAllGroup($user->CustomerId)->result();
        $users = $this->SystemManagement_model->getAllUser($user->CustomerId)->result();

        $activeGroups = unserialize($user->Groups);
        $activeUsers = unserialize($user->Users);

        if ($user == null) {
            $this->session->set_tempdata('err_message', 'Deze groep bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $selectedGroups = array();

            foreach ($_POST['groups'] as $key => $value) {
                array_push($selectedGroups, $value);
            }

            $selectedUsers = array();

            foreach ($_POST['users'] as $key => $value) {
                array_push($selectedUsers, $value);
            }

            $data = array(
                'FirstName' => $_POST['firstname'],
                'Insertion' => $_POST['insertion'],
                'LastName' => $_POST['lastname'],
                'Email' => $_POST['email'],
                'Username' => $_POST['username'],
                'Password' => $_POST['password'],
                'ComputerName' => $_POST['computername'],
                'Groups' => serialize($selectedGroups),
                'users' => serialize($selectedUsers),
                'Comments' => $_POST['comments'],
                'ExchangeUsername' => $_POST['exchangeusername'],
                'ExchangePassword' => $_POST['exchangepassword']
            );

            $this->SystemManagement_model->updateUser($user->Id, $data);

            $this->session->set_tempdata('err_message', 'Gebruiker is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/user/" . $user->CustomerId);
        } else {
            $data['user'] = $user;
            // $data['groups'] = form_dropdown('groups[]', systemManagementUserDropdown($user->CustomerId), unserialize($user->Groups), CLASSSELECTBOOTSTRAP . ' multiple="multiple" id="groups"');
            $data['groups'] = $group;
            $data['users'] = $users;
            $data['activeGroups'] = $activeGroups;
            $data['activeUsers'] = $activeUsers;

            $this->load->view('systemmanagement/updateUser', $data);
        }
    }

    public function deleteUser() {
        if (!isLogged()) {
            redirect('login');
        }

        $userId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $user = $this->SystemManagement_model->getUser($userId)->row();

        if ($user == null) {
            $this->session->set_tempdata('err_message', 'Deze groep bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteUser($user->Id);

        $this->session->set_tempdata('err_message', 'Gebruiker succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/user/' . $user->CustomerId);
    }

    public function share() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['shares'] = $this->SystemManagement_model->getAllShare($customerId)->result();

        $this->load->view('systemmanagement/share', $data);
    }

    public function createShare() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $permissions = array();

            foreach ($_POST['number'] as $value) {

                $group = $_POST['group' . $value];
                $permission = $_POST['permission' . $value];
                $permissions[$group] = $permission;
            }

            $data = array(
                'DriveLetter' => $_POST['driveletter'],
                'Description' => $_POST['description'],
                'NetworkName' => $_POST['networkname'],
                'LocationServer' => $_POST['locationserver'],
                'Permission' => serialize($permissions),
                'Comments' => $_POST['comments'],
                'CustomerId' => $customerId
            );


            $this->SystemManagement_model->createShare($data);
            $this->session->set_tempdata('err_message', 'Share succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/share/$customerId");
        } else {

            $data['share'] = (object) array(
                        'DriveLetter' => '',
                        'Description' => '',
                        'NetworkName' => '',
                        'Permission' => '',
                        'LocationServer' => '',
                        'Comments' => ''
            );

            $this->load->view('systemmanagement/updateShare', $data);
        }
    }

    public function updateShare() {
        if (!isLogged()) {
            redirect('login');
        }

        $shareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $share = $this->SystemManagement_model->getShare($shareId)->row();

        if ($share == null) {
            $this->session->set_tempdata('err_message', 'Deze share bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $permissions = array();

            foreach ($_POST['number'] as $value) {
                $group = $_POST['group' . $value];
                $permission = $_POST['permission' . $value];
                $permissions[$group] = $permission;
            }

            $data = array(
                'DriveLetter' => $_POST['driveletter'],
                'Description' => $_POST['description'],
                'NetworkName' => $_POST['networkname'],
                'LocationServer' => $_POST['locationserver'],
                'Permission' => serialize($permissions),
                'Comments' => $_POST['comments']
            );

            $this->SystemManagement_model->updateShare($share->Id, $data);

            $this->session->set_tempdata('err_message', 'Share is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/share/" . $share->CustomerId);
        } else {
            $data['share'] = $share;

            $this->load->view('systemmanagement/updateShare', $data);
        }
    }

    public function deleteShare() {
        if (!isLogged()) {
            redirect('login');
        }

        $shareId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $share = $this->SystemManagement_model->getShare($shareId)->row();

        if ($share == null) {
            $this->session->set_tempdata('err_message', 'Deze share bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteShare($share->Id);

        $this->session->set_tempdata('err_message', 'Share succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/share/' . $share->CustomerId);
    }

    public function logonScript() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {
            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $data['tpl_timeout'] = $this->session->tempdata('err_timeout');

            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
            $this->session->unset_tempdata('err_timeout');
        }

        $data['logons'] = $this->SystemManagement_model->getAllLogon($customerId)->result();

        $this->load->view('systemmanagement/logonScript', $data);
    }

    public function createLogonScript() {
        if (!isLogged()) {
            redirect('login');
        }

        $customerId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $customer = $this->Customers_model->getCustomer($customerId, $businessId)->row();

        if ($customer == null) {

            $this->session->set_tempdata('err_message', 'Deze klant bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = array(
                'FileName' => $_POST['filename'],
                'NetworkName' => $_POST['networkname'],
                'LocationServer' => $_POST['locationserver'],
                'Script' => $_POST['script'],
                'Comments' => $_POST['comments'],
                'CustomerId' => $customerId
            );

            $this->SystemManagement_model->createLogon($data);
            $this->session->set_tempdata('err_message', 'Login script succesvol aangemaakt', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/logonScript/$customerId");
        } else {

            $data['loginscript'] = (object) array(
                        'FileName' => '',
                        'NetworkName' => '',
                        'LocationServer' => '',
                        'Script' => '',
                        'Comments' => ''
            );
            $this->load->view('systemmanagement/updateLogonScript', $data);
        }
    }

    public function updateLogonScript() {
        if (!isLogged()) {
            redirect('login');
        }

        $logonscriptId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $logonScript = $this->SystemManagement_model->getLogon($logonscriptId)->row();

        if ($logonScript == null) {
            $this->session->set_tempdata('err_message', 'Dit login script bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = array(
                'FileName' => $_POST['filename'],
                'NetworkName' => $_POST['networkname'],
                'LocationServer' => $_POST['locationserver'],
                'Script' => $_POST['script'],
                'Comments' => $_POST['comments']
            );

            $this->SystemManagement_model->updateLogon($logonScript->Id, $data);

            $this->session->set_tempdata('err_message', 'Login script is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect("SystemManagement/logonScript/" . $logonScript->CustomerId);
        } else {
            $data['loginscript'] = $logonScript;

            $this->load->view('systemmanagement/updateLogonScript', $data);
        }
    }

    public function deleteLogonScript() {
        if (!isLogged()) {
            redirect('login');
        }

        $logonscriptId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $logonScript = $this->SystemManagement_model->getLogon($logonscriptId)->row();

        if ($logonScript == null) {
            $this->session->set_tempdata('err_message', 'Dit login script bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect('customers/index/systemmanagement');
        }

        $this->SystemManagement_model->deleteLogonScript($logonScript->Id);

        $this->session->set_tempdata('err_message', 'Login script succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('SystemManagement/logonScript/' . $logonScript->CustomerId);
    }

}
