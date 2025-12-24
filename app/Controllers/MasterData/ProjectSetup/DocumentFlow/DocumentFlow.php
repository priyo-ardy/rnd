<?php

namespace App\Controllers\MasterData\ProjectSetup\DocumentFlow;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\ProjectSetup\DocumentFlow\DocumentFlowModel;
use App\Models\MasterData\ProjectSetup\DocumentFlow\FlowDetailsModel;
use Config\Database;
use Config\Services;

class DocumentFlow extends BaseController
{
    protected $flowModel;
    protected $detailMoldel;
    protected $db;

    public function __construct()
    {
        $this->flowModel = new DocumentFlowModel();
        $this->detailMoldel = new FlowDetailsModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => "Document Flow Setup",
            'flow_level' => $this->flowModel->getFlowLevel(),
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/ProjectSetup/DocumentFlow/document.js' . '"></script>'
            ]
        ];

        return view('MasterData/ProjectSetup/DocumentFlow/index', $data);
    }

    function saveFlowLevel()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/document-flow/save-flow-level',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'DocumentFlow::saveFlowLevel'
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Method Not Allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['name'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Flow level name is required');
            }

            $name = trim($json_data['name']);

            if (strlen($name) > 150) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Flow level name must not exceed 150 characters');
            }

            $id = generate_uuid();

            $data = [
                'id' => $id,
                'name' => $name,
                'created_by' => session('user_name')
            ];

            $this->db->transStart();
            $insert = $this->flowModel->insert($data);
            $this->db->transComplete();

            if (!$insert) {
                logFile(
                    'error',
                    'Failed to insert flow level',
                    [
                        'message' => $this->flowModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'DocumentFlow::saveFlowLevel'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to insert flow level');
            }

            logFile(
                'audit',
                'Successfully inserted flow level',
                [
                    'new_id' => $id,
                    'NIK' => session('user_name')
                ],
                'DocumentFlow::saveFlowLevel'
            );
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occurred',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'DocumentFlow::saveFlowLevel'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error' . $e->getMessage());
        }
    }

    function getFlowLevel()
    {
        $data = $this->flowModel->getFlowLevel();
        return pesan(ResponseInterface::HTTP_OK, 'Success', $data);
    }

    function getDocumentList()
    {
        $json_data = $this->request->getJSON(true);

        if (empty($json_data)) {
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
        }

        if (!isset($json_data['token'])) {
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Token is not available in JSON data');
        }

        $id_flow_level = $json_data['token'];
        $get_document = $this->detailMoldel->getDocumentList($id_flow_level);
        if (empty($get_document) || !$get_document) {
            return pesan(ResponseInterface::HTTP_NOT_FOUND, 'Document not found');
        }

        return pesan(ResponseInterface::HTTP_OK, "Document Found", $get_document);
    }
}
