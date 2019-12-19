<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_emailaddress_to_quotation extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'CustomerMailaddress' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'after' => 'CustomerCountry'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'CustomerMailaddress');
	}
}
