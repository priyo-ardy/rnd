<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAuth extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_name' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_password' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'full_name' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_email' => [
                'type' => "TEXT",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'email_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_phone' => [
                'type' => "TEXT",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'phone_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_status' => [
                'type' => "VARCHAR",
                'constraint' => 1,
                'null' => false,
                'default' => '1',
                'comment' => '1 = Active, 0 = Inactive',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_level' => [
                'type' => "VARCHAR",
                'constraint' => 1,
                'null' => false,
                'default' => '2',
                'comment' => '0 = Super Admin, 1 = Admin, 2 = User',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_image' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'attempts' => [
                'type' => "INT",
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'remark' => [
                'type' => "TEXT",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'last_login' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'login_from' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'user_agent' => [
                'type' => "TEXT",
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'created_at' => [
                'type' => "DATETIME",
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'updated_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ],
            'deleted_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci'
            ]
        ]);

        $this->forge->addKey(['user_id', 'user_name', 'email_hash', 'phone_hash'], true, true);
        $this->forge->createTable('m_user_auth', true);

        $this->db->query("ALTER TABLE m_user_auth ADD INDEX (user_id)");
        $this->db->query("ALTER TABLE m_user_auth ADD INDEX (user_name)");
        $this->db->query("ALTER TABLE m_user_auth ADD INDEX (full_name)");
        $this->db->query("ALTER TABLE m_user_auth ADD INDEX (email_hash)");
        $this->db->query("ALTER TABLE m_user_auth ADD INDEX (phone_hash)");
    }

    public function down()
    {
        $this->forge->dropTable('m_user_auth', true);
    }
}
