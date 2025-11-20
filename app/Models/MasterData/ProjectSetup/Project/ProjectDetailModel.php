<?php

namespace App\Models\MasterData\ProjectSetup\Project;

use CodeIgniter\Model;

class ProjectDetailModel extends Model
{
    protected $table            = 'm_project_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = ['id', 'id_project', 'id_material', 'due_date', 'status', 'remark', 'created_by', 'updated_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getProjectDetails($id_project)
    {
        return $this->select('m_project_details.*, mm.code as material_code, mm.name as material_name, mm.spesifikasi as material_spesifikasi')
            ->join('m_material as mm', 'mm.id = m_project_details.id_material', 'left')
            ->where('id_project', $id_project)
            ->orderBy('mm.code', 'ASC')
            ->findAll();
    }
}
