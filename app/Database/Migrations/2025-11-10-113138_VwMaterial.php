<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwMaterial extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_material");
        $this->db->query("
            CREATE VIEW vw_material AS
            SELECT
                A.*,
                B.name AS nama_satuan,
                C.name AS nama_workshop,
                D.name AS name_route,
                D.route AS production_route
            FROM m_material as A
                LEFT JOIN m_satuan AS B ON A.satuan = B.id
                LEFT JOIN m_workshop AS C ON A.workshop = C.id
                LEFT JOIN m_routes AS D ON A.route = D.id
            WHERE
                A.deleted_at IS NULL
            ORDER BY A.code ASC;
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_material");
    }
}
