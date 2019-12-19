<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rename_reminder_table extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->rename_table('Reminders', 'InvoiceReminders');
	}
	
	public function down()
	{
		$this->dbforge->rename_table('InvoiceReminders', 'Reminders');
	}
}
