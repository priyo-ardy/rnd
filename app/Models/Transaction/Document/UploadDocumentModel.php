<?php

namespace App\Models\Transaction\Document;

use CodeIgniter\Model;

class UploadDocumentModel extends Model
{
    protected $table            = 'm_project_header';
    // protected $primaryKey       = 'id';
    // protected $useAutoIncrement = true;
    // protected $returnType       = 'array';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    // protected $allowedFields    = [];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    public function loadProjectData()
    {
        $builder = $this->db->table($this->table . ' mph');

        $subquery_total_part_no = '(SELECT COUNT(mpd.id) FROM m_project_details mpd WHERE mpd.id_project = mph.id)';
        $subquery_progress = '(SELECT COUNT(mpd2.id) FROM m_project_document mpd2 WHERE mpd2.id_project = mph.id AND mpd2.file_name <> "" AND mpd2.status = "1")';
        $subquery_total_document = '(SELECT COUNT(mpd3.id) FROM m_project_document mpd3 WHERE mpd3.id_project = mph.id)';

        $case_project_type = 'CASE
                                WHEN mph.project_type = "1" THEN "New Project"
                                WHEN mph.project_type = "2" THEN "Transfer Project"
                                WHEN mph.project_type = "3" THEN "Other"
                            END';

        $case_status = 'CASE
                            WHEN mph.status = "0" THEN "Open"
                            WHEN mph.status = "1" THEN "On Progress"
                            WHEN mph.status = "2" THEN "Hold"
                            WHEN mph.status = "3" THEN "Reject"
                            WHEN mph.status = "4" THEN "Close"
                        END';

        $builder->select('mph.*');
        $builder->select('mc.name AS customer_name');
        $builder->select($case_project_type . ' AS type_of_project', false);
        $builder->select($case_status . ' AS status_name', false);
        $builder->select($subquery_total_part_no . ' AS total_part_no', false);
        $builder->select($subquery_progress . ' AS progress', false);
        $builder->select($subquery_total_document . ' AS total_document', false);

        $builder->join('m_customer mc', 'mph.customer = mc.id', 'left');

        $builder->where('mph.status', '1');

        $builder->orderBy('mph.created_at', 'DESC');

        return $builder->get()->getResultObject();
    }
}
