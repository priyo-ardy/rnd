<?php

namespace App\Controllers\MasterData\CommonData\CustomerCategory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\CommonData\CustomerCategory\CustomerCategoryModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class CustomerCategory extends BaseController
{
    protected $categoryModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->categoryModel = new CustomerCategoryModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_customer_category';
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
                <a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit this data" onclick="editData(`' . enkripsi($item->id) . '`);">' . $item->code . '</a>
            ';
            $row[] = $item->name;
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder w-100 d-block" onclick="deleteData(`' . enkripsi($item->id) . '`)">
                    <i class="fa-regular fa-trash-can"></i>
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

        return $this->response->setJSON($output);
    }

    public function index()
    {
        $data = [
            'title' => 'Customer Category',
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/CommonData/CustomerCategory/category.js' . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/CustomerCategory/index', $data);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer_category/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::saveData',
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $id = generate_uuid();
            $code = $this->masterModel->generateCode('m_customer_category', 'code', 'CTG-', 6);
            $name = $this->request->getPost('data_name');
            $remark = $this->request->getPost('data_remark');

            $rules = [
                'data_name' => [
                    'label' => 'Customer Category Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters in length',
                        'max_length' => '{field} must be at most {param} characters in length',
                    ],
                    'filters' => 'trim|strip_tags'
                ]
            ];

            $this->validasi->setRules($rules);

            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode('<br>', $this->validasi->getErrors());

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => $name,
                'remark' => $remark,
                'created_by' => session('user_name'),
            ];

            $this->db->transStart();

            $insert = $this->categoryModel->insert($data);

            $this->db->transComplete();

            if (!$insert) {
                logFile(
                    'error',
                    'Failed to save a new customer category data',
                    [
                        'message' => $this->categoryModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'CustomerCategory::saveData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to save a new customer category data');
            }

            logFile(
                'audit',
                'Success to save a new customer category data',
                [
                    'new_id' => $id,
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::saveData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Success to save a new customer category data');
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
                'CustomerCategory::saveData',
            );
        }
    }

    function getData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer_category/edit',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::getData',
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

            $token = trim($json_data['token']);
            $id_category = dekripsi($token);

            $getData = $this->categoryModel->getData($id_category);
            if (!$getData) {
                logFile(
                    'error',
                    'Customer category data not found',
                    [
                        'keyword' => $token,
                        'NIK' => session('user_name')
                    ],
                    'CustomerCategory::getData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Customer category data not found');
            }

            $data = [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'remark' => $getData->remark
            ];

            return pesan(ResponseInterface::HTTP_OK, 'Success to get customer category data', $data);
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
                'CustomerCategory::getData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error');
        }
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer_category/update',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $this->db->transStart();

            $token = $this->request->getPost('data_token');
            $id_category = dekripsi($token);
            $name = $this->request->getPost('data_name');
            $remark = $this->request->getPost('data_remark');

            $rules = [
                'data_token' => [
                    'label' => 'Customer Category Token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ],
                    'filters' => 'trim|strip_tags'
                ],
                'data_name' => [
                    'label' => 'Customer Category Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters in length',
                        'max_length' => '{field} must be at most {param} characters in length',
                    ],
                    'filters' => 'trim|strip_tags'
                ]
            ];

            $this->validasi->setRules($rules);
            if ($this->validasi->withRequest($this->request)->run() === false) {
                $error_message = implode('<br>', $this->validasi->getErrors());
                logFile(
                    'error',
                    'Validation failed',
                    [
                        'error_message' => $this->validasi->getErrors(),
                        'NIK' => session('user_name')
                    ],
                    'CustomerCategory::updateData',
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation failed : " . $error_message);
            }

            $data = [
                'name' => $name,
                'remark' => $remark,
                'updated_by' => session('user_name')
            ];

            $update = $this->categoryModel->update($id_category, $data);

            $this->db->transComplete();

            if (!$update) {
                logFile(
                    'error',
                    'Failed to update customer category data',
                    [
                        'message' => $this->db->error(),
                        'NIK' => session('user_name')
                    ],
                    'CustomerCategory::updateData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Failed to update customer category data');
            }

            logFile(
                'audit',
                'Success to update customer category data',
                [
                    'keyword' => $token,
                    'data' => $data,
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Success to update customer category data');
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
                'CustomerCategory::updateData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function deleteData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer_category/delete',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::deleteData',
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

            $token = $json_data['token'];
            $id_category = dekripsi($token);

            $getData = $this->categoryModel->where('id', $id_category)->first();
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Data not found');
            }

            $this->db->transStart();
            $delete = $this->categoryModel->delete($id_category);
            $this->db->transComplete();

            if (!$delete) {
                logFile(
                    'error',
                    'Failed to delete customer category data',
                    [
                        'keyword' => $token,
                        'message' => $this->db->error(),
                        'NIK' => session('user_name')
                    ],
                    'CustomerCategory::deleteData',
                );
            }

            logFile(
                'audit',
                'Success to delete customer category data',
                [
                    'keyword' => $token,
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::deleteData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Success to delete customer category data');
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
                'CustomerCategory::deleteData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function exportData()
    {
        if ($this->request->getMethod() !== 'GET') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer_category/export',
                    'method' => $this->request->getMethod(),
                    'expected' => 'GET',
                    'NIK' => session('user_name')
                ],
                'CustomerCategory::exportData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $fileName = 'customer_category' . date("YmdHis") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, remark';

                return $this->masterModel->getChunkedData('m_customer_category', $offset, $limit, 'code', $column);
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
                    'NIK' => session('user_name')
                ],
                'Customer::prevData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function seedData()
    {
        $get = $this->categoryModel->orderBy('code', 'asc')->findAll();

        return pesan(ResponseInterface::HTTP_OK, 'Data was successfully seeded', $get);
    }
}
