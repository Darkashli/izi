<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_key_to_quotation extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'Key' => array(
				'type' =>  'VARCHAR',
				'constraint' => 255,
            )
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'Key');
	}
}
