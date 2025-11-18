<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ApqpLevel extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "dc6000c4-2a22-4a3a-82b7-33412f6a8b41",
                "level" => "1",
                "name" => "Plan & Define Program",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => "admin",
                "deleted_at" => null
            ],
            [
                "id" => "65071500-472d-4cfd-96a6-1e4a2d9e933b",
                "level" => "2",
                "name" => "Product Design & Development",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "1564ffa0-068a-4658-8d54-da023032f984",
                "level" => "3",
                "name" => "Process Design & Development",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "ceb84c70-9e4d-4fe7-abb7-da81783c538f",
                "level" => "4",
                "name" => "Product and Process Validation",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "618a46f7-d77d-48d3-9187-e824ef1b4df3",
                "level" => "5",
                "name" => "Feedback, Assessment & Corrective Action",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_apqp_level')->insertBatch($data);
    }
}
