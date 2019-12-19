<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productgroup extends CI_Controller {

    function __construct() {
        parent::__construct();
         $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        if (!isLogged()) {
            redirect('login');
        }

        redirect('settings/productgroups');
    }
}
