<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Timeperiod_on_regular_invoice extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('Invoice', array(
			'TimePeriod' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
				'after' => 'ExpirationDate'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('Invoice', 'TimePeriod');
	}
}
