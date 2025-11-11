<?php

namespace App\Controllers\MasterData\APQPSetup\APQPLevel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\APQPSetup\APQPLevel\APQPLevelModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class APQPLevel extends BaseController
{
    protected $levelModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->levelModel = new APQPLevelModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_apqp_level';
        $column_order = ['level', 'name', 'remark'];
        $column_search = ['level', 'name', 'remark'];
        $order = array('level' => 'asc');

        $this->dataTable = new DataTableModel(services::request(), $table, $column_order, $column_search, $order);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $row[] = $item->level;
            $row[] = '
                <a href="#" onclick="editData(`' . enkripsi($item->id) . '`)" class="text-decoration-none text-primary fw-bolder">
                    ' . $item->name . '
                </a>
            ';
            $row[] = '
                <button type="button" class="btn btn-primary rounded-0 btn-sm w-100 d-block" onclick="showApprover(`' . enkripsi($item->id) . '`)"><i class="fa-solid fa-file-signature"></i>&ensp;Show Approver</button>
            ';
            $row[] = '
                <button type="button" class="btn btn-primary rounded-0 btn-sm w-100 d-block" onclick="showDetail(`' . enkripsi($item->id) . '`)"><i class="fa-solid fa-file"></i>&ensp;Show Document List</button>
            ';
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . enkripsi($item->id) . '`)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            ';

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
            'title' => 'Setup APQP Level',
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/APQPSetup/APQPLevel/level.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/APQPSetup/APQPLevel/approver.js' . '"></script>'
            ]
        ];

        return view('MasterData/APQPSetup/APQPLevel/index', $data);
    }

    function cekLevel()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-level/level_check',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::cekLevel'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['level'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'APQP Level is not in the JSON data');
            }

            $level = trim($json_data['level']);

            $getData = $this->levelModel->where('level', $level)->first();
            if ($getData) {
                return pesan(ResponseInterface::HTTP_CONFLICT, 'APQP Level already exists');
            }

            return pesan(ResponseInterface::HTTP_OK, 'APQP Level is available');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::cekLevel'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-level/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::saveData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_level' => [
                    'label' => 'APQP Level',
                    'rules' => 'required|numeric|min_length[1]|max_length[2]|is_unique[m_apqp_level.level]',
                    'errors' => [
                        'required' => '{field} is required',
                        'numeric' => '{field} must be numeric',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be at most {param} characters',
                        'is_unique' => '{field} already exists'
                    ]
                ],
                'data_name' => [
                    'label' => 'APQP Level Name',
                    'rules' => 'required|min_length[1]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be at most {param} characters'
                    ]
                ]
            ];

            $this->validasi->setRules($rules);

            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode('<br>', $this->validasi->getErrors());
                logFile(
                    'error',
                    'Validation error',
                    [
                        'message' => $this->validasi->getErrors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::saveData'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $id = generate_uuid();
            $level = trim(strip_tags($this->request->getPost('data_level')));
            $name = trim(strip_tags($this->request->getPost('data_name')));
            $remark = trim(strip_tags($this->request->getPost('data_remark')));

            $data = [
                'id' => $id,
                'level' => $level,
                'name' => $name,
                'remark' => $remark,
                'created_by' => $this->NIK
            ];

            $insert = $this->levelModel->insert($data);

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Database error',
                    [
                        'message' => $this->db->error(),
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::saveData'
                );
            }

            if (!$insert) {
                logFile(
                    'error',
                    'Failed to save APQP Level',
                    [
                        'message' => $this->levelModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::saveData'
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Failed to save APQP Level');
            }

            logFile(
                'audit',
                'Save APQP Level success',
                [
                    'id_level' => $id,
                    'NIK' => session('user_name')
                ],
                'APQPLevel::saveData'
            );

            return pesan(ResponseInterface::HTTP_OK, 'Save APQP Level success');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::saveData'
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
                    'route' => '/apqp-level/get-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::getData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'APQP Token is not available in JSON data');
            }

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            $getData = $this->levelModel->where('id', $id)->first();

            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Level not found');
            }

            return pesan(ResponseInterface::HTTP_OK, 'APQP Level found', $getData);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::getData'
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
                    'route' => '/apqp-level/update',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::updateData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $rules = [
                'data_token' => [
                    'label' => 'APQP Level token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'data_name' => [
                    'label' => 'APQP Level name',
                    'rules' => 'required|min_length[1]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters in length',
                        'max_length' => '{field} must not exceed {param} characters in length'
                    ]
                ]
            ];

            $this->validasi->setRules($rules);

            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode('<br>', $this->validasi->getErrors());

                logFile(
                    'security',
                    'Validation error',
                    [
                        'message' => $error_message,
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::updateData'
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $token = trim(strip_tags($this->request->getPost('data_token')));
            $id = dekripsi($token);
            $name = trim(strip_tags($this->request->getPost('data_name')));
            $remark = trim(strip_tags($this->request->getPost('data_remark')));

            $getData = $this->levelModel->where('id', $id)->first();

            if (!$getData) {
                logFile(
                    'error',
                    'APQP Level not found',
                    [
                        'keyword' => $id,
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::updateData'
                );

                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Level not found');
            }

            $data = [
                'name' => $name,
                'remark' => $remark,
                'updated_by' => $this->NIK
            ];

            $update = $this->levelModel->update($id, $data);

            if (!$update) {
                logFile(
                    'error',
                    'Failed to update APQP Level',
                    [
                        'message' => $this->levelModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::updateData'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to update APQP Level');
            }

            logFile(
                'audit',
                'Update APQP Level',
                [
                    'id' => $id,
                    'NIK' => session('user_name')
                ]
            );

            return pesan(ResponseInterface::HTTP_OK, 'APQP Level updated successfully');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::updateData'
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
                    'route' => '/apqp-level/delete',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::deleteData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'APQP Token is not available in JSON data');
            }

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            $getData = $this->levelModel->where('id', $id)->first();
            if (!$getData) {
                logFile(
                    'error',
                    'APQP Level not found',
                    [
                        'keyword' => $id,
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::deleteData'
                );

                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Level not found');
            }

            $delete = $this->levelModel->delete($id);

            if (!$delete) {
                logFile(
                    'error',
                    'Failed to delete APQP Level',
                    [
                        'message' => $this->levelModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'APQPLevel::deleteData'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete APQP Level');
            }

            logFile(
                'audit',
                'Delete APQP Level',
                [
                    'id' => $id,
                    'NIK' => session('user_name')
                ]
            );

            return pesan(ResponseInterface::HTTP_OK, 'APQP Level deleted successfully');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::deleteData'
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
                    'route' => '/apqp-level/export',
                    'method' => $this->request->getMethod(),
                    'expected' => 'GET',
                    'NIK' => session('user_name')
                ],
                'APQPLevel::exportData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $fileName = 'APQP Level Data ' . date('Y-m-d H:i:s');

            $headers = [
                'Level',
                'Name',
                'Remark',
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'level, name, remark';
                return $this->masterModel->getChunkedData('m_apqp_level', $offset, $limit, 'level', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPLevel::exportData'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }
}
