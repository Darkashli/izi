<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_period_invoice extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column("Quotation", array(
			"RecurringTimePeriod" => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'after' => 'RecurringDescription'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column("Quotation", "RecurringTimePeriod");
	}
}
