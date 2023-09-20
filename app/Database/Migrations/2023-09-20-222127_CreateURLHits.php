<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class CreateURLHits extends Migration
{
    public function __construct(?Forge $forge = null)
    {
        helper('config');
        $smartyConfig = config('Smartyurl');
        if ($smartyConfig->DBGroup !== null) {
            $this->DBGroup = $smartyConfig->DBGroup;
            $this->tables  = $smartyConfig->dbtables;
        }

        parent::__construct($forge);

        $this->tables     = $smartyConfig->dbtables;
        $this->attributes = ($this->db->getPlatform() === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
    }

    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'urlhit_id'          => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'urlhit_urlid'       => ['type' => 'bigint', 'unsigned' => true],
            'urlhit_at'          => ['type' => 'datetime', 'null' => false],
            'urlhit_ip'          => ['type' => 'varchar', 'constraint' => 40, 'null' => false, 'collate' => 'utf8_general_ci'],
            'urlhit_country'     => ['type' => 'varchar', 'constraint' => 2, 'null' => true],
            'urlhit_finaltarget' => ['type' => 'varchar', 'constraint' => 2000, 'null' => false, 'collate' => 'utf8_general_ci'],
            'urlhit_data'        => ['type' => 'json', 'null' => true, 'collate' => 'utf8_general_ci'],
        ]);

        $this->forge->addPrimaryKey('urlhit_id');
        $this->forge->addKey('urlhit_urlid');
        $this->forge->addForeignKey('urlhit_urlid', $this->tables['urls'], 'url_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable($this->tables['urlhits']);
        $this->db->query('ALTER TABLE ' . $this->tables['urlhits'] . ' ENGINE = InnoDB');
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable($this->tables['urlhits'], true);
    }
}
