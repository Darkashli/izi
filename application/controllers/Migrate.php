<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library('migration');
	}
	
	public function index()
	{
		$this->output->enable_profiler(TRUE);
		
		if ($this->migration->current() === FALSE)
		{
			show_error($this->migration->error_string());
		}
	}
}
