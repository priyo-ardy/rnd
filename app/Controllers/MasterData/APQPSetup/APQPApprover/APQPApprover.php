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
            $apqp_level = dekripsi($token);

            $get_approver = $this->approverModel->where('id_apqp', $apqp_level)->first();
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
}
