<?php

namespace App\Models\MasterData\ProjectSetup\Project;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table            = 'm_project_header';
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

    public function insertApqp(array $data)
    {
        return $this->db->table('m_project_apqp')->insertBatch($data);
    }

    public function insertApqpApprover(array $data)
    {
        return $this->db->table('m_project_approver')->insertBatch($data);
    }

    public function insertApqpDocument(array $data)
    {
        return $this->db->table('m_project_document')->insertBatch($data);
    }

    public function getApqp($id_project, $id_material)
    {
        return  $this->db->table('m_project_apqp')
            ->select('m_project_apqp.*, m_apqp_level.name as apqp_level_name')
            ->join('m_apqp_level', 'm_project_apqp.id_apqp = m_apqp_level.id', 'left')
            ->where('id_project', $id_project)
            ->where('id_material', $id_material)
            ->orderBy('baris', 'ASC')
            ->get()
            ->getResultObject();
    }

    public function getApprover($id_project, $id_material)
    {
        return $this->db->table('m_project_approver')
            ->where('id_project', $id_project)
            ->where('id_material', $id_material)
            ->orderBy('baris', 'ASC')
            ->get()
            ->getResultObject();
    }
}
