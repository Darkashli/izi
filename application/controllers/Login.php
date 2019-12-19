<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('users/Users_loginmodel', '', TRUE);
    }

    public function index() {
        if (isLogged()) {
            redirect('dashboard');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $error = 0;

            if (($this->Users_loginmodel->checkUsername(strtolower($_POST['username']))) != true) {
                $tpl_msg = 'Ongeldige gebruikersnaam';
                $tpl_msgtype = 'danger';
                $error++;
            } else {
                $username = strtolower($_POST['username']);
            }

            if (($this->Users_loginmodel->checkPassword($_POST['password'], strtolower($_POST['username']))) == false) {
                $tpl_msg = 'Ongeldig wachtwoord';
                $tpl_msgtype = 'danger';
                $error++;
            }

            if ($error == 0) {
                $user = $this->Users_loginmodel->getInformation($username)->row();

                if ($user->Activated != 1) {
                    $tpl_msg = "Dit account is uitgeschakeld";
                    $tpl_msgtype = 'danger';
                    $error++;
                }

                if ($error == 0) {
                    $this->session->set_userdata('user', $user);

                    if ($this->session->userdata('redirect_back')) {
                        $redirect_url = $this->session->userdata('redirect_back');  // grab value and put into a temp variable so we unset the session value
                        $this->session->unset_userdata('redirect_back');
                        redirect($redirect_url);
                    } else {
                        redirect('dashboard');
                    }
                }
            }
            $this->load->helper('mail');
            $body = "<p>Er is een foutieve inlogpoging gedaan op <a href='" . base_url() . "'>" . base_url() . "</a>.</p>"
                    . "<p><ul><li>Gebruikersnaam: " . $_POST['username'] . "</li><li>Wachtwoord: " . $_POST['password'] . "</li><li>IP: " . $_SERVER['REMOTE_ADDR'] . "</li></ul></p>";
            sendTicket("facturen@commpro.nl", "iziFactuur", "beheer@commpro.nl", "Beheer Commpro", "Nieuwe foutieve inlog", $body, null, null, null, null);
            $data['tpl_msg'] = $tpl_msg;
            $data['tpl_msgtype'] = $tpl_msgtype;

            $this->load->view('login/default', $data);
        } else {
            $data['error'] = "";
            $this->load->view('login/default', $data);
        }
    }

}
