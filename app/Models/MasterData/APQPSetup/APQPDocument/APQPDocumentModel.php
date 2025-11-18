<?php

namespace App\Models\MasterData\APQPSetup\APQPDocument;

use CodeIgniter\Model;

class APQPDocumentModel extends Model
{
    protected $table            = 'm_apqp_document';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = ['id', 'id_apqp', 'baris', 'nama_dokumen', 'uploader', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getDocumentList($id_apqp)
    {
        return $this->select('m_apqp_document.*, m_user_auth.user_name as NIK, m_user_auth.full_name as uploader_name')
            ->join('m_user_auth', 'm_apqp_document.uploader = m_user_auth.user_id', 'left')
            ->where('id_apqp', $id_apqp)
            ->orderBy('baris', 'asc')
            ->findAll();
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

    function getDocumentData($id)
    {
        return $this->select('m_apqp_document.*, m_user_auth.user_name as NIK, m_user_auth.full_name as uploader_name')
            ->join('m_user_auth', 'm_apqp_document.uploader = m_user_auth.user_id', 'left')
            ->where('id', $id)
            ->orderBy('baris', 'asc')
            ->first();
    }
}
