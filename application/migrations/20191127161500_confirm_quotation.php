<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Confirm_quotation extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			"Confirmed" => array(
				'type' => 'BOOL',
				'after' => 'Status',
				'null' => false,
				'default' => 0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'Confirmed');
	}
}
