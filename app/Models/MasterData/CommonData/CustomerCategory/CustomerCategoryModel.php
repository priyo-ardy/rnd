<?php

namespace App\Models\MasterData\CommonData\CustomerCategory;

use CodeIgniter\Model;

class CustomerCategoryModel extends Model
{
    protected $table            = 'm_customer_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = ['id', 'code', 'name', 'remark', 'created_by', 'updated_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getData($id_category)
    {
        return $this->select('id, code, name, remark')
            ->where('id', $id_category)->first();
    }
}
