<?php

namespace App\Controllers\MasterData\CommonData\MaterialCategory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\CommonData\MaterialCategory\MaterialCategoryModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class MaterialCategory extends BaseController
{
    protected $categoryModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->categoryModel = new MaterialCategoryModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_material_category';
        $column_order = ['code', 'name', 'remark'];
        $column_search = ['code', 'name', 'remark'];
        $order = array('code' => 'asc');

        $this->dataTable = new DataTableModel(services::request(), $table, $column_order, $column_search, $order);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $row[] = '
                <a href="#" class="nav-link text-decoration-none text-primary fw-bolder" title="Edit" onclick="editData(' . "'" . enkripsi($item->id) . "'" . ')">
                ' . $item->code . '
                </a>
            ';
            $row[] = $item->name;
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . enkripsi($item->id) . '`)">
                    <i class="bi bi-x"></i>
                </button>';

            $data[] = $row;
        }


        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dataTable->count_all(),
            "recordsFiltered" => $this->dataTable->count_filtered(),
            "data" => $data
        ];

        echo json_encode($output);
    }

    public function index()
    {
        $data = [
            'title' => 'List of Material Category',
            'footer' => [
                '<script src="' . base_url('js/MasterData/CommonData/MaterialCategory/material_category.js') . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/MaterialCategory/index', $data);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/satuan/save-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'MaterialCategory::saveData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $this->validasi->setRules([
                'data_name' => [
                    'label' => 'MaterialCategory Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ]
            ]);

            if ($this->validasi->withRequest($this->request)->run() == FALSE) {
                $error_message = implode('<br>', $this->validasi->getErrors());
                logFile(
                    'error',
                    'Validation error',
                    [
                        'message' => $this->validasi->getErrors(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::saveData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $id = generate_uuid();
            $code = $this->masterModel->generateCode('m_material_category', 'code', 'CTM-', 6);
            $name = trim(strip_tags($this->request->getPost('data_name')));
            $remark = trim(strip_tags($this->request->getPost('data_remark')));

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => $name,
                'remark' => $remark,
                'created_by' => $this->NIK,
            ];

            $insert = $this->categoryModel->insert($data);

            $this->db->transCommit();

            if (!$insert) {
                logFile(
                    'error',
                    'Save error',
                    [
                        'message' => $this->categoryModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::saveData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to save a new material category data');
            }

            logFile(
                'audit',
                'Material category data saved successfully',
                [
                    'routes_id' => $id,
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::saveData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Material category data has been saved');
        } catch (\Exception $e) {
            $this->db->transRollback();
            logFile(
                'error',
                'Error while saving new material category data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::saveData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function getData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/satuan/get-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'MaterialCategory::getData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Material category token is not available in JSON data');
            }

            $token = $json_data['token'];
            $id = dekripsi($token);

            $getData = $this->categoryModel->where('id', $id)->first();
            if (!$getData) {
                logFile(
                    'error',
                    'Material category data not found',
                    [
                        'id' => $id,
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::getData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Material category data not found');
            }

            $data = [
                'token' => enkripsi($id),
                'code' => $getData->code,
                'name' => $getData->name,
                'remark' => $getData->remark
            ];

            return pesan(ResponseInterface::HTTP_OK, 'Data retrieved', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Error while getting material category data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::getData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/satuan/update-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'MaterialCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();

        try {
            $this->validasi->setRules([
                'data_token' => [
                    'label' => 'MaterialCategory token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'data_code' => [
                    'label' => 'MaterialCategory code',
                    'rules' => 'required|min_length[3]|max_length[20]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ],
                'data_name' => [
                    'label' => 'MaterialCategory name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ],
            ]);

            if ($this->validasi->withRequest($this->request)->run() === false) {
                $error_message = implode('<br>', $this->validasi->getErrors());
                logFile(
                    'security',
                    'Invalid data',
                    [
                        'error' => $error_message,
                        'NIK' => $this->NIK
                    ]
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid data', $this->validasi->getErrors());
            }

            $token = trim($this->request->getPost('data_token'));
            $id = dekripsi($token);
            $code = trim($this->request->getPost('data_code'));
            $name = trim($this->request->getPost('data_name'));
            $remark = trim($this->request->getPost('data_remark'));

            $data = [
                'name' => $name,
                'remark' => $remark,
                'updated_by' => $this->NIK
            ];

            $update = $this->categoryModel->update($id, $data);
            $this->db->transCommit();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Error while updating material category data',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::updateData',
                );

                return pesan(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    'Error while updating material category data',
                );
            }

            if (!$update) {
                logFile(
                    'error',
                    'Error while updating material category data',
                    [
                        'error' => $this->categoryModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::updateData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Error while updating material category data');
            }

            logFile(
                'audit',
                'Updated material category data was successfully',
                [
                    'id' => $id,
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Material category data was successfully updated');
        } catch (\Exception $e) {

            logFile(
                'error',
                'Error saat update data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function deleteData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/satuan/delete-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'MaterialCategory::deleteData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Material category token is not available in JSON data');
            }

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            $deleteData = $this->categoryModel->delete($id);
            $this->db->transCommit();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Error while deleting material category data',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::deleteData',
                );
            }

            if (!$deleteData) {
                logFile(
                    'error',
                    'Error while deleting material category data',
                    [
                        'error' => $this->categoryModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'MaterialCategory::deleteData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Error while deleting material category data');
            }

            logFile(
                'audit',
                'Deleted material category data was successfully',
                [
                    'id' => $id,
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::deleteData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Material category data was successfully deleted');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Error during deleting material category data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'MaterialCategory::deleteData',
            );


            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function exportData()
    {
        if ($this->request->getMethod() !== 'GET') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/satuan/export',
                    'method' => $this->request->getMethod(),
                    'expected' => 'GET',
                    'NIK' => session('user_name')
                ],
                'Satuan::exportData',
            );
        }

        try {
            $fileName = 'Material Category Data ' . date('Y-m-d H:i:s');

            $headers = [
                'code',
                'name',
                'remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, remark';
                return $this->masterModel->getChunkedData('m_material_category', $offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Error saat export data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Satuan::exportData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function dataSeed()
    {
        $getData = $this->categoryModel->orderBy('code', 'asc')->findAll();

        return pesan(ResponseInterface::HTTP_OK, 'Data was successfully seeded', $getData);
    }
}
