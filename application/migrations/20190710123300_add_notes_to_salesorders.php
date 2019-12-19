<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_notes_to_salesorders extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->add_column('SalesOrders', array(
			'Note' => array(
				'type' => 'TEXT',
				'null' => true,
				'after' => 'contact'
			)
		));
	}
	
	public function down()
	{
		$this->dbforge->drop_column('SalesOrders', 'Note');
	}
}
