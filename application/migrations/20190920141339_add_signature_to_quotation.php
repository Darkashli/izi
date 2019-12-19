<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_signature_to_quotation extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'Signature' => array(
				'type' => 'longblob'
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'Signature');
	}
}