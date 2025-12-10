<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MaterialTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'kategori' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'code' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'spesifikasi' => [
                'type' => 'TEXT',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'satuan' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'workshop' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'route' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'spq' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 1,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'qty_bag' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => 0,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'color' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'teori_nw' => [
                'type' => "DECIMAL",
                'constraint' => "10,4",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'teori_gw' => [
                'type' => "DECIMAL",
                'constraint' => "10,4",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'teori_shift_capacity' => [
                'type' => "INT",
                'constraint' => 11,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'nw' => [
                'type' => "DECIMAL",
                'constraint' => "10,4",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'gw' => [
                'type' => "DECIMAL",
                'constraint' => "10,4",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'shift_capacity' => [
                'type' => "INT",
                'constraint' => 11,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'remark' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
        ]);

        $this->db->query("DROP TABLE IF EXISTS m_material");
        $this->forge->addKey(['id', 'code'], true, true);
        $this->forge->createTable('m_material', true);

        $this->db->query("ALTER TABLE m_material ADD INDEX (id)");
        $this->db->query("ALTER TABLE m_material ADD INDEX (code)");
        $this->db->query("ALTER TABLE m_material ADD INDEX (satuan)");
        $this->db->query("ALTER TABLE m_material ADD INDEX (workshop)");
    }

    public function down()
    {
        $this->forge->dropTable('m_material', true);
    }
}
