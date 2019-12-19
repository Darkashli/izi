<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_General_conditions extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Business', array(
			'CoditionsGeneralText' => array(
				'type' => 'text'
			),
			'ConditionsSalesText' => array(
				'type' => 'text'
			),
			'ConditionsGeneralPdf' => array(
				'type' => 'varchar',
				'constraint' => 255
			),
			'ConditionsSalesPdf' => array(
				'type' => 'varchar',
				'constraint' => 255
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('Business', array('CoditionsGeneralText', 'ConditionsSalesText', 'ConditionsGeneralPdf', 'ConditionsSalesPdf'));
	}
}
