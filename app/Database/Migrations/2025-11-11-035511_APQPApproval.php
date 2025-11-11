<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class APQPApproval extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'unique' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'id_apqp' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'baris' => [
                'type' => "int",
                'null' => false,
                'default' => 1,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'approver' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'created_at' => [
                'type' => "DATETIME",
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'updated_at' => [
                'type' => "DATETIME",
                'null' => true,
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
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->createTable('m_apqp_approver', true);

        $this->db->query("ALTER TABLE m_apqp_approver ADD INDEX (id)");
        $this->db->query("ALTER TABLE m_apqp_approver ADD INDEX (id_apqp)");
        $this->db->query("ALTER TABLE m_apqp_approver ADD INDEX (approver)");
    }

    public function down()
    {
        $this->forge->dropTable('m_apqp_approver', true);
    }
}
