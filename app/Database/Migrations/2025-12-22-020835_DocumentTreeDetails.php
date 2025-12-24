<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DocumentTreeDetails extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'tree_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'sequence' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'document_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('tree_id');
        $this->forge->createTable('document_tree_details', true);
    }

    public function down()
    {
        $this->forge->dropTable('document_tree_details', true);
    }
}
