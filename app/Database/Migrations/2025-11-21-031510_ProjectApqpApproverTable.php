<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProjectApqpApproverTable extends Migration
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
            'id_approver' => [
                'type' => "VARCHAR",
                'constraint' => 50,
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
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->createTable('m_project_approver', true);

        $this->db->query("ALTER TABLE m_project_approver ADD INDEX (id)");
        $this->db->query("ALTER TABLE m_project_approver ADD INDEX (id_project)");
        $this->db->query("ALTER TABLE m_project_approver ADD INDEX (id_material)");
        $this->db->query("ALTER TABLE m_project_approver ADD INDEX (id_apqp)");
        $this->db->query("ALTER TABLE m_project_approver ADD INDEX (id_approver)");
    }

    public function down()
    {
        $this->forge->dropTable('m_project_approver', true);
    }
}
