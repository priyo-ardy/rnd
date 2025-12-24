<?php

namespace App\Models\MasterData\ProjectSetup\DocumentFlow;

use CodeIgniter\Model;

class FlowDetailsModel extends Model
{
    protected $table            = 'document_tree_details';
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

    public function getDocumentList($id_level)
    {
        return $this->where('tree_id', $id_level)
            ->select('document_tree_details.*, m_apqp_document.nama_dokumen')
            ->join('m_apqp_document', 'document_tree_details.document_id = m_apqp_document.id', 'left')
            ->orderBy('document_tree_details.sequence', 'asc')
            ->get()
            ->getResultObject();
    }
}
