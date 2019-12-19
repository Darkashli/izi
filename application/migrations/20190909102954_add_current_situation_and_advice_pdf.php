<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_current_situation_and_advice_pdf extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'StatusPdf' => array(
				'type' => 'varchar',
				'constraint' => 255
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'StatusPdf');
	}
}
