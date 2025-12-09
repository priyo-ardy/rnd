<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DocumentTreeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'unique' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'node_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'document_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'parent_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'tree_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'document_type' => [
                'type' => 'ENUM',
                'constraint' => ['mandatory', 'optional', 'conditional'],
                'default' => 'mandatory',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'required_docs' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 1,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('document_id');
        $this->forge->addForeignKey('parent_id', 't_document_tree', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('t_document_tree', true);
    }

    public function down()
    {
        $this->forge->dropTable('t_document_tree', true);
    }
}
