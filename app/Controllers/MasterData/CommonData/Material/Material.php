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

        $table = 'vw_material';
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

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => 'material/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => $this->NIK
                ],
                'Material::saveData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, "Request not allowed");
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_code' => [
                    'label' => 'Material code',
                    'rules' => 'required|is_unique[m_material.code]|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => '{field} is required',
                        'is_unique' => '{field} already registered',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be less than {param} characters'
                    ]
                ],
                'data_name' => [
                    'label' => 'Material name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be less than {param} characters'
                    ]
                ],
                'data_spesifikasi' => [
                    'label' => 'Material specification',
                    'rules' => 'required|min_length[3]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters'
                    ]
                ],
                'data_satuan' => [
                    'label' => 'UoM',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'data_workshop' => [
                    'label' => 'Workshop',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
            ];

            $this->validasi->setRules($rules);

            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode('<br>', $this->validasi->getErrors());

                logFile(
                    'error',
                    "Validation error",
                    [
                        'message' => $this->validasi->getErrors(),
                        'NIK' => $this->NIK
                    ],
                    'Material::saveData'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation errors " . $error_message);
            }

            $id = generate_uuid();
            $code = trim(strip_tags($this->request->getPost('data_code')));
            $name = trim(strip_tags($this->request->getPost('data_name')));
            $spesifikasi = trim(strip_tags($this->request->getPost('data_spesifikasi')));
            $satuan = trim(strip_tags($this->request->getPost('data_satuan')));
            $workshop = trim(strip_tags($this->request->getPost('data_workshop')));
            $route = trim(strip_tags($this->request->getPost('data_route')));
            $color = trim(strip_tags($this->request->getPost('data_color')));
            $teori_nw = trim(strip_tags($this->request->getPost('data_teori_nw')));
            $teori_gw = trim(strip_tags($this->request->getPost('data_teori_gw')));
            $teori_shift_capacity = trim(strip_tags($this->request->getPost('data_teori_shift_capacity')));
            $nw = trim(strip_tags($this->request->getPost('data_nw')));
            $gw = trim(strip_tags($this->request->getPost('data_gw')));
            $shift_capacity = trim(strip_tags($this->request->getPost('data_shift_capacity')));
            $remark = trim(strip_tags($this->request->getPost('data_remark')));

            if ($teori_nw > $teori_gw) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Theoritical Net Weight cannot greater than Theoritical Gross Weight");
            }

            if ($nw > $gw) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Actual Net Weight cannot greater than Actual Gross Weight");
            }

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => $name,
                'spesifikasi' => $spesifikasi,
                'satuan' => $satuan,
                'workshop' => $workshop,
                'route' => $route,
                'color' => $color,
                'teori_nw' => $teori_nw,
                'teori_gw' => $teori_gw,
                'teori_shift_capacity' => $teori_shift_capacity,
                'nw' => $nw,
                'gw' => $gw,
                'shift_capacity' => $shift_capacity,
                'remark' => $remark,
                'created_by' => $this->NIK
            ];

            $insert = $this->materialModel->insert($data);
            $this->db->transCommit();
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Insert transaction failed',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'Material::saveData'
                );
            }

            if (!$insert) {
                logFile(
                    'error',
                    'Failed to save a new material data',
                    [
                        'message' => $this->materialModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'Material::saveData'
                );

                throw new \Exception("Failed to save a new material data");
            }

            logFile(
                'audit',
                'Successfully saved a new material data',
                [
                    'material_id' => $id,
                    'material_code' => $code,
                    'NIK' => $this->NIK
                ],
            );

            return pesan(ResponseInterface::HTTP_OK, "Successfully saved a new material data");
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Material::saveData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }
}
