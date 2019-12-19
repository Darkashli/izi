<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Current_situation_and_advice extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Quotation', array(
			'CurrentSituationAndAdvice' => array(
				'type' => 'text'
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Quotation', 'CurrentSituationAndAdvice');
	}
}
