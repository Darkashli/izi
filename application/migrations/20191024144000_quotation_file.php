<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Quotation_file extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'Id' => array(
                'type' =>  'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),

            'Name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),

            'DisplayFileName' => array(
				'type' => 'varchar',
				'constraint' => 255
			),

            'QuotationId' => array(
                'type' =>  'INT',
                'constraint' => 11,
            ),

            'BusinessId' => array(
                'type' =>  'INT',
                'constraint' => 11,
            )
        ));
            $this->dbforge->add_key('Id', TRUE);
            $this->dbforge->create_table('QuotationFile');
    }

    public function down()
    {
        $this->dbforge->drop_table('QuotationFile');
    }
}