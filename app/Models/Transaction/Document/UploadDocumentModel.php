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

    function loadProjectByCategory()
    {
        // 1. Panggil koneksi database
        $db = \Config\Database::connect();

        // --- SUBQUERY A: Menghitung Total Project ---
        $subProject = $db->table('m_project_header mph')
            ->select('mph.category, COUNT(mph.id) as total_project')
            ->groupBy('mph.category')
            ->getCompiledSelect();

        // --- SUBQUERY B: Menghitung Project Details (PN) ---
        $subDetails = $db->table('m_project_details mpd')
            ->select('mph.category, COUNT(mpd.id) as total_pn')
            ->join('m_project_header mph', 'mpd.id_project = mph.id')
            ->groupBy('mph.category')
            ->getCompiledSelect();

        // --- SUBQUERY C: Menghitung Statistik Dokumen (Complex Logic) ---
        // Note: Kita set parameter kedua select() menjadi `false` agar CI4 tidak menimpa tanda kurung dengan backticks.
        $subDocs = $db->table('m_project_document mpd')
            ->select('mph.category')
            ->select('COUNT(mpd.id) as total_task')
            ->select("SUM(CASE WHEN mpd.file_name <> '' THEN 1 ELSE 0 END) as task_close", false)
            ->select("SUM(CASE WHEN mpd.file_name = '' THEN 1 ELSE 0 END) as task_progress", false)
            ->select("SUM(CASE 
                WHEN (mpd.file_name = '' OR mpd.file_name IS NULL) 
                AND mpd.due_date < CURDATE() THEN 1 
                ELSE 0 
              END) as task_overdue", false)
            ->join('m_project_header mph', 'mpd.id_project = mph.id')
            ->where('mph.status', '1')
            ->whereNotIn('mpd.status', ['2', '3', '4'])
            ->groupBy('mph.category')
            ->getCompiledSelect();

        // --- MAIN QUERY: Menggabungkan Semuanya ---
        $builder = $db->table('m_customer_category mcc');

        $builder->select('mcc.*');
        // Gunakan COALESCE agar null menjadi 0
        $builder->select('COALESCE(proj.total_project, 0) as total_project');
        $builder->select('COALESCE(det.total_pn, 0) as total_pn');
        $builder->select('COALESCE(doc.total_task, 0) as total_task');
        $builder->select('COALESCE(doc.task_close, 0) as task_close');
        $builder->select('COALESCE(doc.task_progress, 0) as task_progress');
        $builder->select('COALESCE(doc.task_overdue, 0) as task_overdue');

        // Lakukan Join dengan Subquery yang sudah di-compile di atas
        $builder->join("($subProject) proj", 'mcc.id = proj.category', 'left');
        $builder->join("($subDetails) det", 'mcc.id = det.category', 'left');
        $builder->join("($subDocs) doc", 'mcc.id = doc.category', 'left');

        $builder->orderBy('mcc.code', 'ASC');

        // Eksekusi
        $results = $builder->get()->getResult();

        // Untuk debugging, jika ingin melihat query asli yang dihasilkan:
        // echo $db->getLastQuery();

        return $results;
    }
}
