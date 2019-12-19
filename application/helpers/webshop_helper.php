<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function hasWebshop()
{
	$ci = & get_instance();
	$ci->load->database();
	
	$businessId = $ci->session->userdata('user')->BusinessId;
	
	return $ci->db->get_where('Webshop', array('BusinessId' => $businessId, 'Active' => 1))->row() != null;
}
