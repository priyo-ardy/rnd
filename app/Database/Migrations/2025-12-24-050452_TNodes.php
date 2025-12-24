<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TNodes extends Migration
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
            'stage_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'node_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'is_sequence' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'sequence_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'updated_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'deleted_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('stage_id', 't_stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('t_nodes', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_nodes', true);
    }
}
