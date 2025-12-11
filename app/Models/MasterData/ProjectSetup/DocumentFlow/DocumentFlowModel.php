<?php

namespace App\Models\MasterData\ProjectSetup\DocumentFlow;

use CodeIgniter\Model;

class DocumentFlowModel extends Model
{
    protected $table            = 'document_tree_level';
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

    public function getFlowLevel()
    {
        return $this->orderBy('level', 'asc')
            ->select('id, level, name')
            ->findAll();
    }
}
