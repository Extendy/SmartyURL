<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

/**
 * This migration will for the feature Shared URL between system users
 */
class AddUrlSharedColumn extends Migration
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
        $fields = [
            'url_shared' => [
                'type'    => 'BOOLEAN',
                'default' => 0, // Default value for existing URLs
                'after'   => 'url_hitscounter', // Specify the position after url_hitscounter
            ],
        ];

        $this->forge->addColumn($this->tables['urls'], $fields);
    }

    public function down()
    {
        $this->forge->dropColumn($this->tables['urls'], 'url_shared');
    }
}
