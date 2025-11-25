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
            ->select('m_project_apqp.*, m_apqp_level.name as apqp_level_name', true)
            ->join('m_apqp_level', 'm_project_apqp.id_apqp = m_apqp_level.id', 'left')
            ->where('id_project', $id_project, true)
            ->where('id_material', $id_material, true)
            ->orderBy('baris', 'ASC')
            ->get()
            ->getResultObject();
    }

    public function getApprover($id_project, $id_material, $id_apqp)
    {
        return $this->db->table('m_project_approver')
            ->select('m_project_approver.*,m_user_auth.user_name as NIK, m_user_auth.full_name as approver_name, m_apqp_level.name as apqp_level_name', true)
            ->join('m_user_auth', 'm_project_approver.id_approver = m_user_auth.user_id', 'left', true)
            ->join('m_apqp_level', 'm_project_approver.id_apqp = m_apqp_level.id', 'left')
            ->where('id_project', $id_project, true)
            ->where('id_material', $id_material, true)
            ->where('id_apqp', $id_apqp, true)
            ->orderBy('baris', 'ASC')
            ->get()
            ->getResultObject();
    }

    function updateApprover($id, $data)
    {
        return $this->db->table('m_project_approver')->update($data, ['id' => $id]);
    }

    function getApproverById($id)
    {
        return $this->db->table('m_project_approver')
            ->select('m_project_approver.*,m_user_auth.user_name as NIK, m_user_auth.full_name as approver_name', true)
            ->join('m_user_auth', 'm_project_approver.id_approver = m_user_auth.user_id', 'left')
            ->where('id', $id, true)
            ->get()
            ->getFirstRow();
    }

    function deleteApprover($id)
    {
        return $this->db->table('m_project_approver')->delete(['id' => $id]);
    }

    function getDocumentList($id_project, $id_material, $id_apqp)
    {
        return $this->db->table('m_project_document')
            ->select('m_project_document.*, m_apqp_document.nama_dokumen as document_name, m_user_auth.user_name as NIK, m_user_auth.full_name as uploader_name', true)
            ->where('m_project_document.id_project', $id_project, true)
            ->where('m_project_document.id_material', $id_material, true)
            ->where('m_project_document.id_apqp', $id_apqp, true)
            ->join('m_apqp_document', 'm_project_document.id_document = m_apqp_document.id', 'left')
            ->join('m_user_auth', 'm_project_document.id_uploader = m_user_auth.user_id', 'left')
            ->orderBy('m_project_document.baris', 'ASC')
            ->get()
            ->getResultArray();
    }
}
