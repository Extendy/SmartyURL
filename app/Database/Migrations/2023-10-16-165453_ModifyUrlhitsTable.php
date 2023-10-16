<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class ModifyUrlhitsTable extends Migration
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
        $this->forge->addColumn($this->tables['urlhits'], [
            'urlhit_useragent' => [
                'type'    => 'text',
                'default' => null,
                'null'    => true,
                'after'   => 'urlhit_country',
            ],
        ]);

        $this->forge->addColumn($this->tables['urlhits'], [
            'urlhit_visitordevice' => [
                'type'       => 'ENUM',
                'constraint' => ['phone', 'tablet', 'computer'],
                'null'       => true,
                'after'      => 'urlhit_country',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn($this->tables['urlhits'], 'urlhit_useragent');
        $this->forge->dropColumn($this->tables['urlhits'], 'urlhit_visitordevice');
    }
}
