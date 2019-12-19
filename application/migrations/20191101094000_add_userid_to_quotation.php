<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_userid_to_quotation extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'UserId' => array(
				'type' =>  'INT',
				'constraint' => 11,
				'AFTER' => 'Id'
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'UserId');
	}
}
