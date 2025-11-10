<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkshopData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "b74a6c47-af61-4753-832f-2e24e03f0cce",
                "code" => "WRS-000001",
                "name" => "Injection",
                "remark" => "",
                "created_at" => "2025-11-10 14:09:00",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:10:46",
                "updated_by" => "admin",
                "deleted_at" => null
            ],
            [
                "id" => "0aed708b-e962-4242-9d7b-b13ce34e93cf",
                "code" => "WRS-000002",
                "name" => "Assembly",
                "remark" => "",
                "created_at" => "2025-11-10 14:09:25",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:09:25",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "ac5474b8-6451-4e7d-b623-a55ddea3acb6",
                "code" => "WRS-000003",
                "name" => "CCD",
                "remark" => "",
                "created_at" => "2025-11-10 14:09:49",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:09:49",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "d27c3bff-1d7c-42b2-bc11-ef00ce35616f",
                "code" => "WRS-000004",
                "name" => "Laser Printing",
                "remark" => "",
                "created_at" => "2025-11-10 14:09:54",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:09:54",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "85b02102-bca2-4b57-b66b-ae0343124df4",
                "code" => "WRS-000005",
                "name" => "Waterbath",
                "remark" => "",
                "created_at" => "2025-11-10 14:09:59",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:09:59",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "6e737658-8afb-4751-b24c-944e1383812a",
                "code" => "WRS-000006",
                "name" => "Mixing",
                "remark" => "",
                "created_at" => "2025-11-10 14:10:04",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:10:04",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "1b4abe07-c397-48f0-8792-b16f4305c549",
                "code" => "WRS-000007",
                "name" => "Raw Material",
                "remark" => "",
                "created_at" => "2025-11-10 14:10:08",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:10:08",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "8fa9696e-0f9b-458e-b2ba-1c43f6f37f02",
                "code" => "WRS-000008",
                "name" => "Child Parts",
                "remark" => "",
                "created_at" => "2025-11-10 14:10:13",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:10:13",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "95c3c216-cac4-4afa-8395-62fca970abe3",
                "code" => "WRS-000009",
                "name" => "Inspection",
                "remark" => "",
                "created_at" => "2025-11-10 14:10:18",
                "created_by" => "admin",
                "updated_at" => "2025-11-10 14:10:18",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_workshop')->insertBatch($data);
    }
}
