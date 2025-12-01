<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProjectApqpDocumentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_project' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_material' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_apqp' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'baris' => [
                'type' => "INT",
                'constraint' => 1,
                'null' => false,
                'default' => 1,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_document' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_uploader' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'status' => [
                'type' => "VARCHAR",
                'constraint' => 1,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'file_name' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'upload_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
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
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('id_project');
        $this->forge->addKey('id_material');
        $this->forge->addKey('id_apqp');
        $this->forge->addKey('id_document');
        $this->forge->addKey('id_uploader');
        $this->forge->createTable('m_project_document', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_project_document', true);
    }
}
