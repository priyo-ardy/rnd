<?php

namespace App\Controllers\Transaction\Document;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Transaction\Document\UploadDocumentModel;
use App\Models\MasterData\ProjectSetup\Project\ProjectModel;
use App\Models\MasterData\ProjectSetup\Project\ProjectDetailModel;
use App\Models\MasterData\CommonData\CustomerCategory\CustomerCategoryModel;
use Config\Database;
use Config\Services;

class UploadDocument extends BaseController
{
    protected $documentModel;
    protected $projectModel;
    protected $detailModel;
    protected $categoryModel;
    protected $apqpDocument;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->documentModel = new UploadDocumentModel();
        $this->projectModel = new ProjectModel();
        $this->detailModel = new ProjectDetailModel();
        $this->categoryModel = new CustomerCategoryModel();
        $this->db = Database::connect();
        $this->validasi = Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'APQP Document',
            'data_list' => $this->documentModel->loadProjectByCategory(),
            'footer' => [
                '<script src="' . base_url() . 'js/Transaction/Document/UploadDocument/document.js' . '"></script>'
            ]
        ];

        return view('Transaction/Document/UploadDocument/index', $data);
    }

    public function getProject($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/document/project/(:any)',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UploadDocument::getDetail'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $category = dekripsi($token);
        $checkCategory = $this->categoryModel->where('id', $category)->first();
        if (!$checkCategory) {
            $category_name = '';
        }

        $category_name = $checkCategory->name;

        $data = [
            'title' => 'List of Project - ' . $category_name,
            'project_list' => $this->projectModel->loadProjectData($category),
            'footer' => []
        ];

        return view('Transaction/Document/UploadDocument/project', $data);
    }

    function getPartList($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/document/part/(:any)',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UploadDocument::getDetail'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $id_project = dekripsi($token);

            $data = [
                'title' => 'Project Details',
                'part_list' => $this->detailModel->getProjectDetails($id_project),
                'footer' => []
            ];

            return view('Transaction/Document/UploadDocument/part', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'UploadDocument::getDetail'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected Error' . $e->getMessage());
        }
    }

    function uploadDocument($token)
    {
        try {
            $id_material = dekripsi($token);

            $apqp = $this->projectModel->getApqpByMaterial($id_material);
            $apqpIds = array_column($apqp, 'id_apqp');

            $allDocuments = $this->projectModel->getDocumentByMaterial($id_material, $apqpIds);

            $docMap = [];
            foreach ($allDocuments as $doc) {
                $parentId = $doc->id_apqp;
                $docMap[$parentId][] = $doc;
            }

            $data = [
                'title' => 'Upload Document',
                'apqp' => $apqp,
                'document' => $docMap,
                'footer' => []
            ];

            return view('Transaction/Document/UploadDocument/upload', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'UploadDocument::getDetail'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected Error' . $e->getMessage());
        }
    }
}
