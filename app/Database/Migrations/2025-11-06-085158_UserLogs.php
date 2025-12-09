<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'context' => [
                'type' => 'TEXT',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'source' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('level');
        $this->forge->addKey('source');
        $this->forge->addKey('created_at');

        $this->forge->createTable('logfile', true);
    }

    public function down()
    {
        $this->forge->dropTable('logfile', true);
    }
}
