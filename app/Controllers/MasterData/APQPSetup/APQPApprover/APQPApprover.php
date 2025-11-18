<?php

namespace App\Controllers\MasterData\APQPSetup\APQPApprover;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\APQPSetup\APQPApprover\APQPApproverModel;
use App\Models\MasterData\APQPSetup\APQPLevel\APQPLevelModel;
use App\Models\Master\MasterModel;
use Config\Services;
use Config\Database;

class APQPApprover extends BaseController
{
    protected $approverModel;
    protected $levelModel;
    protected $masterModel;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->approverModel = new APQPApproverModel();
        $this->levelModel = new APQPLevelModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();
    }

    public function index()
    {
        //
    }

    function loadApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-approver/load_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPApprover::loadApprover'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['apqp_level'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'APQP Level is not available in JSON data');
            }

            $token = trim($json_data['apqp_level']);
            $id_apqp = dekripsi($token);

            $get_approver = $this->approverModel->loadApprover($id_apqp);
            return pesan(ResponseInterface::HTTP_OK, 'Success', $get_approver);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'APQPApprover::loadApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured');
        }
    }

    function saveApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-approver/save_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPApprover::saveApprover'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        // $this->db->transStart();
        try {
            $token = trim($this->request->getPost('apqp_token'));
            $approver = $this->request->getPost('approver');
            $id_apqp = dekripsi($token);

            if (count($approver) == 0) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Approver is empty, save failed');
            }

            $total_approver = count($approver);
            $data = [];
            $baris = 1;

            for ($i = 0; $i < $total_approver; $i++) {
                $data[] = [
                    'id' => generate_uuid(),
                    'id_apqp' => $id_apqp,
                    'baris' => $baris,
                    'approver' => $approver[$i],
                    'created_by' => $this->NIK
                ];

                $baris++;
            }

            $insert = $this->approverModel->insertBatch($data);
            if (!$insert) {
                // $this->db->transRollback();

                logFile(
                    'error',
                    'Save failed',
                    [
                        'message' => $this->approverModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPApprover::saveApprover'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Save failed');
            }

            logFile(
                'audit',
                'Successfully saved approver data',
                [
                    'message' => $data,
                    'NIK' => $this->NIK
                ],
                'APQPApprover::saveApprover'
            );

            return pesan(ResponseInterface::HTTP_OK, 'Saved success', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'APQPApprover::saveApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function updateApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-approver/update_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPApprover::updateApprover'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token']) || !isset($json_data['approver'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Token & Approver is not available in JSON data');
            }

            $id = $json_data['token'];
            $approver = $json_data['approver'];

            $data = [
                'approver' => $approver,
                'updated_by' => $this->NIK
            ];

            $update = $this->approverModel->update($id, $data);
            if (!$update) {
                logFile(
                    'error',
                    'Update failed',
                    [
                        'message' => $this->approverModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPApprover::updateApprover'
                );
            }


            $get = $this->approverModel->getApproverData($id);

            logFile(
                'audit',
                'Successfully updated approver data',
                [
                    'message' => $get,
                    'NIK' => $this->NIK
                ],
                'APQPApprover::updateApprover'
            );

            return pesan(ResponseInterface::HTTP_OK, 'Updated success', $get);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'APQPApprover::updateApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function addApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-approver/add_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPApprover::addApprover'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token']) || !isset($json_data['approver'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Token & Approver is not available in JSON data');
            }

            $token = $json_data['token'];
            $id_apqp = dekripsi($token);
            $approver = $json_data['approver'];

            $getLastRow = $this->approverModel->getLastRow($id_apqp);

            $data = [
                'id' => generate_uuid(),
                'id_apqp' => $id_apqp,
                'baris' => $getLastRow,
                'approver' => $approver,
                'created_by' => $this->NIK,
            ];

            $insert = $this->approverModel->insert($data);
            if (!$insert) {
                logFile(
                    'error',
                    'Insert failed',
                    [
                        'message' => $this->approverModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPApprover::addApprover'
                );
            }
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'APQPApprover::addApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function deleteApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-approver/delete_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPApprover::deleteApprover'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Token is not available in JSON data');
            }

            $id = $json_data['token'];
            $delete = $this->approverModel->delete($id, true);

            if (!$delete) {
                logFile(
                    'error',
                    'Delete failed',
                    [
                        'message' => $this->approverModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPApprover::deleteApprover'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Delete failed');
            }

            logFile(
                'audit',
                'Successfully deleted approver data',
                [
                    'id_approver' => $id,
                    'NIK' => $this->NIK
                ],
                'APQPApprover::deleteApprover'
            );

            return pesan(ResponseInterface::HTTP_OK, 'Successfully deleted approver data');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'APQPApprover::deleteApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }
}
