<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoutesData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "e38fe301-0458-4a32-b912-8f9cf3042544",
                "code" => "RTS-000001",
                "name" => "Direct FG From Injection",
                "route" => "Injection - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "b232e31d-5b03-4775-b0b5-983a2f2e5d42",
                "code" => "RTS-000002",
                "name" => "Injection, Inspection",
                "route" => "Injection - Inspection - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "013fb442-eaaa-48fa-a42a-c0875ad818a8",
                "code" => "RTS-000003",
                "name" => "Injection, Waterbath, Inspection",
                "route" => "Injection - Waterbath - Inspection - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "7797e7a3-0008-4769-800e-cce89503a118",
                "code" => "RTS-000004",
                "name" => "Injection, Assembly, Inspection",
                "route" => "Injection - Assembly - Inspection - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "3f58ae5c-e778-40f4-96d5-b03673594d0c",
                "code" => "RTS-000005",
                "name" => "Injection, Waterbath, Assembly, Inspection",
                "route" => "Injection - Waterbath - Assembly - Inspection - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "ae8587d0-58c0-4616-91e3-c2bcfd809f42",
                "code" => "RTS-000006",
                "name" => "Injection, Assemby",
                "route" => "Injection - Assembly - FG",
                "remark" => "",
                "created_at" => date("Y-m-d H:i:s"),
                "created_by" => "admin",
                "updated_at" => date("Y-m-d H:i:s"),
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_routes')->insertBatch($data);
    }
}
