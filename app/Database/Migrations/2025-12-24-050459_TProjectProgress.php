<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TProjectProgress extends Migration
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
            'project_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'node_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'status' => [
                'type' => "ENUM",
                'constraint' => ['pending', 'in_progress', 'completed'],
                'null' => false,
                'default' => 'pending',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addForeignKey('project_id', 'm_project_header', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('node_id', 't_nodes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('t_project_progress', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_project_progress', true);
    }
}
