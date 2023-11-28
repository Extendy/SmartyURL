<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use PhpCsFixer\Config;

/**
 * This class add usergroups and their permissions in database to be used bt setting() function
 * instead of using app/Config/AuthGroups.php
 */
class AddDefaultSettings1 extends Migration
{
    public function __construct(?Forge $forge = null)
    {
        $this->config  = config('Settings');
        $this->DBGroup = $this->config->database['group'] ?? null;

        parent::__construct($forge);

        $this->attributes = ($this->db->getPlatform() === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
    }

    public function up()
    {
        // add default usergroups into database
        service('settings')->set('AuthGroups.groups', setting('AuthGroups.groups'));
        service('settings')->set('AuthGroups.matrix', setting('AuthGroups.matrix'));
    }

    public function down()
    {
        // remove Config\AuthGroups from settings table
        $this->db->table($this->config->database['table'])->where('class', 'Config\AuthGroups')->delete();
    }
}
