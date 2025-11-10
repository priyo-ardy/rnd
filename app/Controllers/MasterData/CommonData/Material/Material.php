<?php

namespace App\Controllers\MasterData\CommonData\Material;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\CommonData\Workshop\WorkshopModel;
use App\Models\MasterData\CommonData\Satuan\SatuanModel;
use App\Models\MasterData\CommonData\ProductionRoutes\ProductionRoutesModel;
use App\Models\MasterData\CommonData\MaterialCategory\MaterialCategoryModel;
use App\Models\MasterData\CommonData\Material\MaterialModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class Material extends BaseController
{
    protected $workshopModel;
    protected $categoryModel;
    protected $materialModel;
    protected $satuanModel;
    protected $masterModel;
    protected $routesModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->workshopModel = new WorkshopModel();
        $this->categoryModel = new MaterialCategoryModel();
        $this->materialModel = new MaterialModel();
        $this->masterModel = new MasterModel();
        $this->satuanModel = new SatuanModel();
        $this->routesModel = new ProductionRoutesModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_material';
        $column_order = [];
        $column_search = [];
        $order = array('code' => 'ASC');

        $this->dataTable = new DataTableModel(Services::request(), $table, $column_order, $column_search, $order);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $data[] = $row;
        }

        $output = [
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->dataTable->count_all(),
            "recordsFiltered" => $this->dataTable->count_filtered(),
            "data" => $data
        ];

        echo json_encode($output);
    }

    public function index()
    {
        $data = [
            'title' => 'List of Material',
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/CommonData/Material/material.js' . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/Material/index', $data);
    }

    function addData()
    {
        $data = [
            'title' => "Add New Material",
            'satuan' => $this->satuanModel->orderBy('code', 'ASC')->findAll(),
            'workshop' => $this->workshopModel->orderBy('code', 'ASC')->findAll(),
            'route' => $this->routesModel->orderBy('code', 'ASC')->findAll(),
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/CommonData/Material/add.js' . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/Material/add', $data);
    }

    function cekMaterialCode()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => 'material/check_code',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => $this->NIK
                ],
                'Material::cekMaterialCode'
            );
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Invalid JSON data");
            }

            if (!isset($json_data['code'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material code is not available on JSON request");
            }

            $code = $json_data['code'];

            $findData = $this->materialModel->where('code', $code)->first();
            if (!$findData) {
                return pesan(ResponseInterface::HTTP_OK, "Material code is available");
            } else {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, "Material code is not available");
            }
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Material::cekMaterialCode'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }
}
