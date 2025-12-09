<?php

namespace App\Models\MasterData\CommonData\Material;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table            = 'm_material';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getPrevData($code)
    {
        $this->where('code <', $code);
        $this->orderBy('code', 'desc');
        $this->limit(1);
        return $this->first();
    }

    public function getNextData($code)
    {
        $this->where('code >', $code);
        $this->orderBy('code', 'asc');
        $this->limit(1);
        return $this->first();
    }

    public function getMaterialList()
    {
        $usedMaterialIds = $this->db->table('m_project_details')->select('id_material')->get()->getResultArray();
        $usedIds = array_column($usedMaterialIds, 'id_material');

        return $this->builder()
            ->where('kategori', 'd7e6cc88-39c0-4fd7-8acc-1c545108fcb2')
            ->whereNotIn('id', $usedIds)
            ->orderBy('code', 'asc')
            ->get()
            ->getResultObject();
    }
}
