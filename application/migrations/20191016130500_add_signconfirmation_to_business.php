<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_signconfirmation_to_business extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('Business', array(
			'SignConfirmationForCustomer' => array(
				'type' => 'longblob'
			),

			'SignConfirmationForCollaborator' => array(
				'type' => 'longblob'
			)
		));

	}

	public function down()
	{
		$this->dbforge->drop_column('Business', array('SignConfirmationForCustomer', 'SignConfirmationForCollaborator'));
	}
}