<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerCategoryData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => generate_uuid(),
                "code" => "CTG-000001",
                "name" => "YAZAKI PROJECT",
                "remark" => "Untuk semua customer PASI beserta Affiliatenya",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => "admin",
                "deleted_at" => null
            ],
            [
                "id" => generate_uuid(),
                "code" => "CTG-000002",
                "name" => "SUMITOMO PROJECT",
                "remark" => "Untuk semua project SUMITOMO beserta affiliatenya",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => "admin",
                "deleted_at" => null
            ],
            [
                "id" => generate_uuid(),
                "code" => "CTG-000003",
                "name" => "OTHER PROJECT",
                "remark" => "Untuk semua project selain YAZAKI Group & SUMITOMO Group",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => "admin",
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_customer_category')->insertBatch($data);
    }
}
