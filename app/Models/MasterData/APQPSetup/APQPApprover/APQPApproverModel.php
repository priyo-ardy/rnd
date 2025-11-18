<?php

namespace App\Models\MasterData\APQPSetup\APQPApprover;

use CodeIgniter\Model;

class APQPApproverModel extends Model
{
    protected $table            = 'm_apqp_approver';
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

    public function loadApprover($id_apqp)
    {
        return
            $this->select('m_apqp_approver.*, m_user_auth.user_name as NIK, m_user_auth.full_name as approver_name')
            ->orderBy('baris', 'asc')
            ->where('id_apqp', $id_apqp)
            ->join('m_user_auth', 'm_apqp_approver.approver = m_user_auth.user_id', 'left')
            ->findAll();
    }

    public function getApproverData($id)
    {
        return $this->select('m_apqp_approver.*, m_user_auth.user_name as NIK, m_user_auth.full_name as approver_name')
            ->where('id', $id)
            ->join('m_user_auth', 'm_apqp_approver.approver = m_user_auth.user_id', 'left')
            ->first();
    }

    function getLastRow($id_apqp)
    {
        $query = $this->where('id_apqp', $id_apqp)
            ->orderBy('baris', 'desc')
            ->first();

        if ($query) {
            return $query->baris + 1;
        } else {
            return 1;
        }
    }
}
