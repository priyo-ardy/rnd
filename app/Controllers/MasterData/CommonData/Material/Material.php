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
use Psr\Http\Message\ResponseInterface as MessageResponseInterface;

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
        $column_order = ['nama_kategori', 'code', 'name', 'spesifikasi', 'nama_satuan', 'nama_workshop', 'production_route', 'color', 'teori_nw', 'teori_gw', 'teori_shift_capacity', 'nw', 'gw', 'shift_capacity', 'remark'];
        $column_search = ['nama_kategori', 'code', 'name', 'spesifikasi', 'nama_satuan', 'nama_workshop', 'production_route', 'color', 'teori_nw', 'teori_gw', 'teori_shift_capacity', 'nw', 'gw', 'shift_capacity', 'remark'];
        $order = array('code' => 'ASC');

        $this->dataTable = new DataTableModel(Services::request(), $table, $column_order, $column_search, $order);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $row[] = $item->nama_kategori;
            $row[] = '
                <a href="' . base_url() . 'material/show/' . enkripsi($item->id) . '" title="Click to edit this data" class="text-primary fw-bolder text-decoration-none">
                    ' . $item->code . '
                </a>
            ';
            $row[] = $item->name;
            $row[] = $item->spesifikasi;
            $row[] = $item->nama_satuan;
            $row[] = $item->nama_workshop;
            $row[] = $item->color;
            $row[] = $item->production_route;
            $row[] = $item->teori_nw;
            $row[] = $item->teori_gw;
            $row[] = $item->teori_shift_capacity;
            $row[] = $item->nw;
            $row[] = $item->gw;
            $row[] = $item->shift_capacity;
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . enkripsi($item->id) . '`)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            ';


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
            'kategori' => $this->categoryModel->orderBy('code', 'ASC')->findAll(),
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

    function changeMaterialCode()
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
                'Material::changeMaterialCode'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, "Request not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Invalid JSON data");
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material Token is not available on JSON request");
            }

            $token = $json_data['token'];
            $id = dekripsi($token);
            $kode = $json_data['code'];

            $getData = $this->materialModel->where('id', $id)->first();
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, "Material data not found");
            }

            if ($getData->code !== $kode) {
                $getCode = $this->materialModel->where('code', $kode)->first();
                if ($getCode) {
                    return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material code already exist");
                }
            }

            return pesan(ResponseInterface::HTTP_OK, "Material code is available");
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
                'Material::changeMaterialCode'
            );
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
                'data_kategori' => [
                    'label' => 'Material category',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
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
            $kategori = trim(strip_tags($this->request->getPost('data_kategori')));
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
                'kategori' => $kategori,
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

    function getData($token)
    {
        $id = dekripsi($token);
        $getData = $this->materialModel->where('id', $id)->first();

        $data = [
            'title' => "Show Material Data | {$getData->code}",
            'satuan' => $this->satuanModel->orderBy('code', 'ASC')->findAll(),
            'workshop' => $this->workshopModel->orderBy('code', 'ASC')->findAll(),
            'route' => $this->routesModel->orderBy('code', 'ASC')->findAll(),
            'kategori' => $this->categoryModel->orderBy('code', 'ASC')->findAll(),
            'data' => $getData,
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/CommonData/Material/show.js' . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/Material/show', $data);
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => 'material/update',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => $this->NIK
                ],
                'Material::updateData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, "Request not allowed");
        }

        try {
            $rules = [
                'data_token' => [
                    'label' => 'Material token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'data_kategori' => [
                    'label' => 'Material category',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'data_code' => [
                    'label' => 'Material code',
                    'rules' => 'required|min_length[3]|max_length[100]',
                    'errors' => [
                        'required' => '{field} is required',
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
                    'Validation error',
                    [
                        'error' => $error_message,
                        'NIK' => $this->NIK
                    ],
                    'Material::updateData'
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $token = $this->request->getPost('data_token');
            $id = dekripsi($token);
            $kategori = trim(strip_tags($this->request->getPost('data_kategori')));
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

            $getData = $this->materialModel->where('id', $id)->first();
            if (!$getData) {
                logFile(
                    'security',
                    'Update failed, material data not found',
                    [
                        'keywords' => $id,
                        'NIK' => $this->NIK
                    ],
                    'Material::updateData'
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material data not found");
            }

            if ($getData->code !== $code) {
                $cekKode = $this->materialModel->where('code', $code)->first();
                if ($cekKode) {
                    logFile(
                        'security',
                        'Update failed, material code already exist',
                        [
                            'keywords' => $code,
                            'NIK' => $this->NIK
                        ],
                        'Material::updateData'
                    );

                    return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material code already exist");
                }
            }

            $data = [
                'kategori' => $kategori,
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

            $update = $this->materialModel->update($id, $data);
            $this->db->transCommit();
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                logFile(
                    'error',
                    'Update failed',
                    [
                        'message' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'Material::updateData'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Update failed");
            }

            if (!$update) {
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Update failed, there was an error during processing your request");
            }

            logFile(
                'audit',
                'Update success',
                [
                    'id_material' => $id,
                    'NIK' => $this->NIK
                ],
                'Material::updateData'
            );

            return pesan(ResponseInterface::HTTP_OK, "Update success");
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
                'Material::updateData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }

    function deleteData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/material/delete',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Material::deleteData',
            );

            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request method not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Invalid JSON data");
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material Token is not available on JSON request");
            }

            $token = $json_data['token'];
            $id = dekripsi($token);

            $getData = $this->materialModel->where('id', $id)->first();
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Material data not found");
            }

            $delete = $this->materialModel->delete($id);
            if (!$delete) {
                logFile(
                    'error',
                    'Delete failed',
                    [
                        'message' => $this->materialModel->error(),
                        'NIK' => $this->NIK
                    ],
                    'Material::deleteData'
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Delete failed, there was an error during processing your request");
            }

            logFile(
                'audit',
                'Delete success',
                [
                    'id_material' => $id,
                    'NIK' => $this->NIK
                ],
                'Material::deleteData'
            );

            return pesan(ResponseInterface::HTTP_OK, "Delete success");
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
                'Material::deleteData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }

    function exportData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/material/export',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Material::exportData'
            );
        }

        try {
            $fileName = 'UoM Data ' . date('Y-m-d H:i:s');

            $headers = [
                'Category',
                'Code',
                'Name',
                'Specification',
                'UoM',
                'Workshop',
                'Product Color',
                'Production Routes',
                'Theoritical Net Weight',
                'Theoritical Gross Weight',
                'Theoritical Shift Capacity',
                'Actual Net Weight',
                'Actual Gross Weight',
                'Actual Shift Capacity',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'nama_workshop, code, name, spesifikasi, nama_satuan, color, production_route, teori_nw, teori_gw, teori_shift_capacity, nw, gw, shift_capacity, remark';
                return $this->masterModel->getChunkedData('vw_material', $offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
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
                'Material::exportData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }

    function prevData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/material/prev',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Material::prevData'
            );
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['code'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Material code is not available in JSON data');
            }

            $code = $json_data['code'];

            $getPrevData = $this->materialModel->getPrevData($code);
            if (!$getPrevData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, "You are at the first data");
            }

            return pesan(ResponseInterface::HTTP_OK, "Success", [
                'token' => enkripsi($getPrevData->id)
            ]);
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
                'Material::prevData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }

    function nextData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/material/prev',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Material::prevData'
            );
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['code'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Material code is not available in JSON data');
            }

            $code = $json_data['code'];

            $getNextData = $this->materialModel->getNextData($code);
            if (!$getNextData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, "You are at the last data");
            }

            return pesan(ResponseInterface::HTTP_OK, "Success", [
                'token' => enkripsi($getNextData->id)
            ]);
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
                'Material::prevData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected error occured " . $e->getMessage());
        }
    }
}
