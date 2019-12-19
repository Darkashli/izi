<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Neutral_gender_contacts extends CI_Migration {
	
	public function up()
	{
		$contacts = $this->db->get('Contacts')->result();
		
		$this->dbforge->drop_column('Contacts', 'Sex');
		$this->dbforge->add_column('Contacts', array(
			'Sex' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'after' => 'LastName',
				'null' => true,
				'default' => null
			)
		));
		
		foreach ($contacts as $contact) {
			$data = array(
				'Sex' => $contact->Sex,
			);
			$this->db->where('Id', $contact->Id);
			$this->db->update('Contacts', $data);
		}
	}
	
	public function down()
	{
		$contacts = $this->db->get('Contacts')->result();
		
		$this->dbforge->drop_column('Contacts', 'Sex');
		$this->dbforge->add_column('Contacts', array(
			'Sex' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'after' => 'LastName',
				'null' => false,
				'default' => 'male'
			)
		));
		
		foreach ($contacts as $contact) {
			if (empty($contact->Sex)) {
				// Skip this because the default gender is male.
				continue;
			}
			
			$data = array(
				'Sex' => $contact->Sex,
			);
			$this->db->where('Id', $contact->Id);
			$this->db->update('Contacts', $data);
		}
	}
}
