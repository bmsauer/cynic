<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitKvAndAuthTables extends Migration
{

    protected $DBGroup = 'tests';
    
    public function up()
    {
        //kv
        $this->forge->addField([
            'k' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
            'v' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
            'username' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
            'app' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
        ]);
        $this->forge->createTable('kv');

        //auth
        $this->forge->addField([
            'username' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
            'password' => [
                'type'           => 'VARCHAR',
                'constraint'     => '128',
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
                'default' => 'user',
            ],
        ]);
        $this->forge->addField("created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->forge->createTable('auth');
        
    }

    public function down()
    {
        $this->forge->dropTable('kv');
        $this->forge->dropTable('auth');
    }
}