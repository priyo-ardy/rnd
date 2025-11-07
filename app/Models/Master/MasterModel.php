<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterModel extends Model
{
    function generateCode($table, $column, $prefix, $length)
    {
        $builder = $this->db->table($table);

        $query = $builder->select('RIGHT(' . $column . ', ' . $length . ') AS kode')
            ->orderBy($column, 'DESC')
            ->limit(1)
            ->get();

        $result = $query->getRow();

        if ($result) {
            $kode = (int) $result->kode + 1;
        } else {
            $kode = 1;
        }

        $generated_code = $prefix . str_pad($kode, $length, '0', STR_PAD_LEFT);

        return $generated_code;
    }

    public function getChunkedData($table, $offset, $limit, $order, $column)
    {
        return $this->db->table($table)
            ->select($column)
            ->limit($limit, $offset)
            ->orderBy($order, 'ASC')
            ->get()
            ->getResultArray();
    }
}
