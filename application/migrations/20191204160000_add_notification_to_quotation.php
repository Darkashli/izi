<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_notification_to_quotation extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			"Notification" => array(
				'type' => 'BOOL',
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'Notification');
	}
}
