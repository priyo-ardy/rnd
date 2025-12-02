<?php

namespace App\Controllers\Transaction\Document;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Transaction\Document\UploadDocumentModel;
use App\Models\MasterData\ProjectSetup\Project\ProjectDetailModel;
use App\Models\MasterData\CommonData\CustomerCategory\CustomerCategoryModel;
use Config\Database;
use Config\Services;

class UploadDocument extends BaseController
{
    protected $documentModel;
    protected $detailModel;
    protected $categoryModel;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->documentModel = new UploadDocumentModel();
        $this->detailModel = new ProjectDetailModel();
        $this->categoryModel = new CustomerCategoryModel();
        $this->db = Database::connect();
        $this->validasi = Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'APQP Document',
            'category_list' => $this->categoryModel->orderBy('code', 'ASC')->findAll(),
            'footer' => [
                '<script src="' . base_url() . 'js/Transaction/Document/UploadDocument/document.js' . '"></script>'
            ]
        ];

        return view('Transaction/Document/UploadDocument/index', $data);
    }

    public function getDetail($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/document/get_detail',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UploadDocument::getDetail'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $id_project = dekripsi($token);

        $data = [
            'title' => 'Project Details',
            'project_detail' => $this->detailModel->getProjectDetails($id_project),
            'footer' => []
        ];

        return view('Transaction/Document/UploadDocument/detail', $data);
    }
}
