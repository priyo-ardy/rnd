<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TStages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'stage_name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'color_code' => [
                'type' => "VARCHAR",
                'constraint' => 20,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'sort_order' => [
                'type' => "INT",
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

        $this->forge->addKey('id', true, true);
        $this->forge->createTable('t_stages', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_stages', true);
    }
}
