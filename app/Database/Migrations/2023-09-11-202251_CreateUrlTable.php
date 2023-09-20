<?php
/**
 * Creating the URL Core tables for SmartyURL
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class CreateUrlTable extends Migration
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

        // creating urls table
        $this->forge->addField([
            'url_id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'url_identifier' => ['type' => 'varchar', 'constraint' => 50, 'null' => false, 'collate' => 'utf8_general_ci'],
            'url_user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'url_title'      => ['type' => 'varchar', 'constraint' => 200, 'null' => true, 'collate' => 'utf8_general_ci'],
            'url_targeturl'  => ['type' => 'varchar', 'constraint' => 2000, 'null' => false, 'collate' => 'utf8_general_ci'],
            'url_conditions' => ['type' => 'json', 'null' => true, 'collate' => 'utf8_general_ci'],
            'created_at'     => ['type' => 'datetime', 'null' => false],
            'updated_at'     => ['type' => 'datetime', 'null' => false],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('url_id');
        $this->forge->addUniqueKey('url_identifier');
        $this->forge->addKey('url_user_id');
        $this->forge->addForeignKey('url_user_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable($this->tables['urls']);
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . ' ENGINE = InnoDB');
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . ' ADD FULLTEXT (url_targeturl)');
        $this->db->query('ALTER TABLE ' . $this->tables['urls'] . ' ADD FULLTEXT (url_title)');

        // creating urltags table
        $this->forge->addField([
            'tag_id'      => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'tag_name'    => ['type' => 'char', 'constraint' => 50],
            'tag_user_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'datetime', 'null' => false],
            'updated_at'  => ['type' => 'datetime', 'null' => false],
            'deleted_at'  => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('tag_id');
        $this->forge->addKey('tag_user_id');
        $this->forge->addForeignKey('tag_user_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable($this->tables['urltags']);
        $this->db->query('ALTER TABLE ' . $this->tables['urltags'] . ' ENGINE = InnoDB');
        $this->db->query('ALTER TABLE ' . $this->tables['urltags'] . ' ADD FULLTEXT (tag_name)');
        // make two columns unique together (tag_name and tag_user_id) , user cannot ceate the same twice but other users can
        // create the same tag
        $this->db->query('ALTER TABLE ' . $this->tables['urltags'] . ' ADD CONSTRAINT user_tag UNIQUE (tag_name, tag_user_id)');

        /**
         * Creating url tags data table
         */
        $this->forge->addField([
            'tagdata_id' => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'url_id'     => ['type' => 'bigint', 'unsigned' => true],
            'tag_id'     => ['type' => 'bigint', 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey('tagdata_id');
        $this->forge->addKey('url_id');
        $this->forge->addForeignKey('url_id', $this->tables['urls'], 'url_id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('tag_id');
        $this->forge->addForeignKey('tag_id', $this->tables['urltags'], 'tag_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable($this->tables['urltagsdata']);
        $this->db->query('ALTER TABLE ' . $this->tables['urltagsdata'] . ' ENGINE = InnoDB');
        $this->db->query('ALTER TABLE ' . $this->tables['urltagsdata'] . ' ADD CONSTRAINT url_id_tag_id UNIQUE (url_id, tag_id)');
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable($this->tables['urls'], true);
        $this->forge->dropTable($this->tables['urltags'], true);
        $this->forge->dropTable($this->tables['urltagsdata'], true);
    }
}
