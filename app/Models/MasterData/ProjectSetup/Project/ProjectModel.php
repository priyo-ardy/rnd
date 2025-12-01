<?php

namespace App\Models\MasterData\ProjectSetup\Project;

use CodeIgniter\Model;
use CodeIgniter\HTTP\ResponseInterface;

class ProjectModel extends Model
{
    protected $table            = 'm_project_header';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = ['id', 'code', 'name', 'project_type', 'status', 'customer', 'remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function loadProjectData()
    {
        // $this->db->table($this->table) secara otomatis memulai builder untuk tabel ini
        $builder = $this->db->table($this->table . ' mph');

        // 1. Definisikan ekspresi raw
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

        // 2. Klausa SELECT
        $builder->select('mph.*');
        $builder->select('mc.name AS customer_name');
        $builder->select($case_project_type . ' AS type_of_project', false);
        $builder->select($case_status . ' AS status_name', false);
        $builder->select($subquery_total_part_no . ' AS total_part_no', false);
        $builder->select($subquery_progress . ' AS progress', false);
        $builder->select($subquery_total_document . ' AS total_document', false);

        // 3. Klausa JOIN
        $builder->join('m_customer mc', 'mph.customer = mc.id', 'left');

        // 4. Klausa ORDER BY
        $builder->orderBy('mph.created_at', 'DESC');

        // 5. Eksekusi dan kembalikan hasilnya
        return $builder->get()->getResultObject();
        // Anda juga bisa menggunakan $builder->get()->getResult(); untuk objek
    }

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

    function updateDokumen($id_dokumen, $data)
    {
        return $this->db->table('m_project_document')->update($data, ['id' => $id_dokumen]);
    }

    function cekData($id_project)
    {
        return $this->db->table('m_project_document')
            ->where('id_project', $id_project)
            ->where('due_date', null)
            ->get()
            ->getResultObject();
    }

    function cekProjectStatus($id_project)
    {
        return $this->db->table('m_project_header')
            ->select('m_project_header.status')
            ->where('id', $id_project)
            ->where('m_project_header.status', '1')
            ->get()
            ->getResultObject();
    }

    function updateProjectStatus($id_project, $status = '1')
    {
        $updated_by = session('user_name');
        $error_messages = [];

        try {
            $this->db->transStart();
            $update_header = $this->update($id_project, ['status' => $status, 'updated_by' => $updated_by]);

            if (!$update_header) {
                $error_messages[] = [
                    'table' => 'm_project_header',
                    'message' => 'Failed to update project status at m_project_header table. Please try again.',
                    'error' => $this->db->error()
                ];

                log_message('error', 'Failed to update project status at m_project_header table. Please try again.' . $this->db->error());
            }

            $child_tables = [
                'm_project_details' => 'id_project',
                'm_project_apqp' => 'id_project',
                'm_project_approver' => 'id_project',
                'm_project_document' => 'id_project',
            ];

            foreach ($child_tables as $table_name => $fk_column) {
                $update_result = $this->db->table($table_name)
                    ->where([$fk_column => $id_project])
                    ->update(['status' => $status, 'updated_by' => $updated_by]);

                if ($update_result === false) {
                    $error_messages[] = [
                        'table' => $table_name,
                        'message' => 'Failed to update project status at ' . $table_name . ' table. Please try again.',
                        'error' => $this->db->error()
                    ];

                    log_message('error', 'Failed to update project status at ' . $table_name . ' table. Please try again.' . $this->db->error());
                }
            }

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                $error_messages[] = [
                    'table' => 'Transaction',
                    'message' => 'Failed to update project status. Please try again.',
                    'error' => $this->db->error()
                ];

                log_message('error', 'Failed to update project status. Please try again.' . $this->db->error());

                return $error_messages;
            }

            if (!empty($error_messages)) {
                return $error_messages;
            }

            return true;
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected Error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'ProjectModel::updateProjectStatus'
            );

            $error_messages[] = [
                'table' => 'Exception',
                'message' => 'Unexpected Error ' . $e->getMessage(),
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ];

            return $error_messages;
        }
    }

    function setupUploadDocument()
    {
        try {
            $this->db->transStart();

            $getDocument = $this->db->table('m_project_document')
                ->where('id_project', $this->id_project)
                ->get()
                ->getResultObject();
            $this->db->transComplete();
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected Error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'ProjectModel::setupUploadDocument'
            );

            return false;
        }
    }
}
