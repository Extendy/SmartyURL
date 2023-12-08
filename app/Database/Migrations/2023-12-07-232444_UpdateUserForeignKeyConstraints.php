<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

/**
 * This migration will fix the FOREIGN KEY for URL and make sure when user account deleted
 * then all URL of the user will be deleted
 */
class UpdateUserForeignKeyConstraints extends Migration
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
        // Update foreign key constraints for url table
        // to delete url when user deleted
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . ' DROP FOREIGN KEY urls_url_user_id_foreign');
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . '  ADD CONSTRAINT urls_url_user_id_foreign
            FOREIGN KEY (url_user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // rollback to prev version of url table -> Update foreign key constraints for url table
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . ' DROP FOREIGN KEY urls_url_user_id_foreign');
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . '  ADD CONSTRAINT urls_url_user_id_foreign
            FOREIGN KEY (url_user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE SET NULL');
    }
}
