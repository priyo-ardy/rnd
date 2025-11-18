<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MaterialCategoryData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "8b749999-4d79-446e-86ce-b2206fc643a2",
                "code" => "CTM-000001",
                "name" => "Raw Material",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "f25ede5a-82dc-445e-940f-64e459883e45",
                "code" => "CTM-000002",
                "name" => "Mixing Material",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "dc1980af-d339-409e-a428-71ef27ed943c",
                "code" => "CTM-000003",
                "name" => "Child Parts",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "7287bc37-9d10-4f71-90e5-339dd2199210",
                "code" => "CTM-000004",
                "name" => "Auxiliary",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "d7e6cc88-39c0-4fd7-8acc-1c545108fcb2",
                "code" => "CTM-000005",
                "name" => "Finish Goods",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "64155930-ca3f-4799-9620-5ebb7b36a768",
                "code" => "CTM-000006",
                "name" => "Semi-finished Goods",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => "admin",
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_material_category')->insertBatch($data);
    }
}
