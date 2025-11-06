<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthData extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => generate_uuid(),
                'user_name' => 'admin',
                'user_password' => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name' => 'Super Administrator',
                'user_email' => enkripsi('admin@localhost'),
                'email_hash' => email_hash('admin@localhost'),
                'user_phone' => enkripsi('081234567890'),
                'phone_hash' => phone_hash('081234567890'),
                'user_status' => '1',
                'user_image' => 'default.png',
                'attempts' => 0,
                'remark' => 'From database seeder',
                'last_login' => null,
                'login_from' => '',
                'user_agent' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 'admin',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => '',
                'deleted_at' => null,
            ],
            [
                'user_id' => generate_uuid(),
                'user_name' => 'user',
                'user_password' => password_hash('user123', PASSWORD_DEFAULT),
                'full_name' => 'User',
                'user_email' => enkripsi('user@localhost'),
                'email_hash' => email_hash('user@localhost'),
                'user_phone' => enkripsi('085678901234'),
                'phone_hash' => phone_hash('085678901234'),
                'user_status' => '1',
                'user_image' => 'default.png',
                'attempts' => 0,
                'remark' => 'From database seeder',
                'last_login' => null,
                'login_from' => '',
                'user_agent' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 'admin',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => '',
                'deleted_at' => null,
            ]
        ];

        $this->db->table('m_user_auth')->insertBatch($data);
    }
}
