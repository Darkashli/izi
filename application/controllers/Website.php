<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('customers/Customers_model', '', TRUE);
        $this->load->model('website/Website_model', '', TRUE);
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

        if ($this->session->tempdata('err_message')) {
            $data['tpl_msg'] = $this->session->tempdata('err_message');
            $data['tpl_msgtype'] = $this->session->tempdata('err_messagetype');
            $this->session->unset_tempdata('err_message');
            $this->session->unset_tempdata('err_messagetype');
        }

        $data['websites'] = $this->Website_model->getAll($customerId)->result();

        $this->load->view('website/default', $data);
    }

    public function create() {
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
            $extentions = array();

            foreach ($_POST['number'] as $value) {

                $extention = (object) array(
                            'Name' => $_POST['name' . $value],
                            'Kind' => $_POST['kindextention' . $value],
                            'Version' => $_POST['version' . $value]
                );

                $extentions[$value] = $extention;
            }

            $data = array(
                'DomainName' => $_POST['domainname'],
                'IpAddress' => $_POST['ipaddress'],
                'Provider' => $_POST['provider'],
                'Hosting' => $_POST['hosting'],
                'HostingUsername' => $_POST['hostingusername'],
                'HostingPassword' => $_POST['hostingpassword'],
                'NameServer1' => $_POST['nameserver1'],
                'NameServer2' => $_POST['nameserver2'],
                'FTPHost' => $_POST['ftphost'],
                'FTPPort' => $_POST['ftpport'],
                'FTPUsername' => $_POST['ftpusername'],
                'FTPPassword' => $_POST['ftppassword'],
                'CMS' => $_POST['cms'],
                'CMSVersion' => $_POST['cmsversion'],
                'UpdatesInstallatron' => $_POST['updatesinstallatron'],
                'LatestUpdate' => strtotime($_POST['latestupdate']),
                'GoogleAnalytics' => $_POST['googleanalytics'],
                'GoogleSearch' => $_POST['googlesearch'],
                'Extentions' => serialize($extentions),
                'CustomerId' => $customerId,
                'BusinessId' => $businessId
            );

            $this->Website_model->create($data);
            $this->session->set_tempdata('err_message', 'Website is succesvol toegevoegd', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect('Website/index/' . $customerId);
        } else {

            $data['updatesinstallatron'] = form_dropdown('updatesinstallatron', unserialize(YESNODROPDOWN), '', CLASSDROPDOWN);
            $data['googleAnalytics'] = form_dropdown('googleanalytics', unserialize(YESNODROPDOWN), '', CLASSDROPDOWN);
            $data['googleSearch'] = form_dropdown('googlesearch', unserialize(YESNODROPDOWN), '', CLASSDROPDOWN);
            $data['type'] = 'create';

            $data['website'] = (object) array(
                        'DomainName' => '',
                        'IpAddress' => '',
                        'Provider' => '',
                        'Hosting' => '',
                        'HostingUsername' => '',
                        'HostingPassword' => '',
                        'NameServer1' => '',
                        'NameServer2' => '',
                        'FTPHost' => '',
                        'FTPPort' => '',
                        'FTPUsername' => '',
                        'FTPPassword' => '',
                        'CMS' => '',
                        'CMSVersion' => '',
                        'UpdatesInstallatron' => '',
                        'LatestUpdate' => strtotime(date('d-m-Y')),
                        'GoogleAnalytics' => '',
                        'GoogleSearch' => '',
                        'Extentions' => ''
            );

            $this->load->view('website/create', $data);
        }
    }

    public function edit() {
        if (!isLogged()) {
            redirect('login');
        }

        $websiteId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $website = $this->Website_model->getWebsite($websiteId, $businessId)->row();

        if ($website == null) {

            $this->session->set_tempdata('err_message', 'Deze website bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/website");
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $extentions = array();

            foreach ($_POST['number'] as $value) {

                $extention = (object) array(
                            'Name' => $_POST['name' . $value],
                            'Kind' => $_POST['kindextention' . $value],
                            'Version' => $_POST['version' . $value]
                );

                $extentions[$value] = $extention;
            }


            $data = array(
                'DomainName' => $_POST['domainname'],
                'IpAddress' => $_POST['ipaddress'],
                'Provider' => $_POST['provider'],
                'Hosting' => $_POST['hosting'],
                'HostingUsername' => $_POST['hostingusername'],
                'HostingPassword' => $_POST['hostingpassword'],
                'NameServer1' => $_POST['nameserver1'],
                'NameServer2' => $_POST['nameserver2'],
                'FTPHost' => $_POST['ftphost'],
                'FTPPort' => $_POST['ftpport'],
                'FTPUsername' => $_POST['ftpusername'],
                'FTPPassword' => $_POST['ftppassword'],
                'CMS' => $_POST['cms'],
                'CMSVersion' => $_POST['cmsversion'],
                'UpdatesInstallatron' => $_POST['updatesinstallatron'],
                'LatestUpdate' => strtotime($_POST['latestupdate']),
                'GoogleAnalytics' => $_POST['googleanalytics'],
                'GoogleSearch' => $_POST['googlesearch'],
                'Extentions' => serialize($extentions)
            );

            $this->Website_model->update($website->Id, $data);

            $this->session->set_tempdata('err_message', 'Website is succesvol aangepast', 300);
            $this->session->set_tempdata('err_messagetype', 'success', 300);
            redirect('Website/index/' . $website->CustomerId);
        } else {
            $data['updatesinstallatron'] = form_dropdown('updatesinstallatron', unserialize(YESNODROPDOWN), $website->UpdatesInstallatron, CLASSDROPDOWN);
            $data['googleAnalytics'] = form_dropdown('googleanalytics', unserialize(YESNODROPDOWN), $website->GoogleAnalytics, CLASSDROPDOWN);
            $data['googleSearch'] = form_dropdown('googlesearch', unserialize(YESNODROPDOWN), $website->GoogleSearch, CLASSDROPDOWN);
            $data['type'] = 'edit';
            $data['website'] = $website;

            $this->load->view('website/create', $data);
        }
    }

    public function delete() {
        if (!isLogged()) {
            redirect('login');
        }

        $websiteId = $this->uri->segment(3);
        $businessId = $this->session->userdata('user')->BusinessId;

        $website = $this->Website_model->getWebsite($websiteId, $businessId)->row();

        if ($website == null) {

            $this->session->set_tempdata('err_message', 'Deze website bestaat niet', 300);
            $this->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/website");
        }

        $this->Website_model->deleteWebsite($websiteId, $businessId);

        $this->session->set_tempdata('err_message', 'Website succesvol verwijderd', 300);
        $this->session->set_tempdata('err_messagetype', 'success', 300);
        redirect('Website/index/' . $website->CustomerId);
    }

}
