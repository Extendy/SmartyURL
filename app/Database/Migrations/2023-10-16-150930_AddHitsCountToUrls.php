<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddHitsCountToUrls extends Migration
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
        $this->forge->addColumn($this->tables['urls'], [
            'url_hitscounter' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
                'null'     => false,
                'after'    => 'url_conditions',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn($this->tables['urls'], 'url_hitscounter');
    }
}
