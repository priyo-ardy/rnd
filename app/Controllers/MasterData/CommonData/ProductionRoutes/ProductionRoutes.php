<?php

namespace App\Controllers\MasterData\CommonData\ProductionRoutes;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use App\Models\MasterData\CommonData\ProductionRoutes\ProductionRoutesModel;
use Config\Services;
use Config\Database;

class ProductionRoutes extends BaseController
{
    protected $routesModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->routesModel = new ProductionRoutesModel();
        $this->masterModel = new MasterModel();
        $this->db = Database::connect();
        $this->validasi = Services::validation();

        $table = 'm_routes';
        $column_order = ['code', 'name', 'route', 'remark'];
        $column_search = ['code', 'name', 'route', 'remark'];
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
                <a href="#" onclick="editData(`' . enkripsi($item->id) . '`)" class="text-decoration-none text-primary fw-bolder">
                    ' . $item->code . '
                </a>
            ';
            $row[] = $item->name;
            $row[] = $item->route;
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . enkripsi($item->id) . '`)">
                    <i class="bi bi-x"></i>
                </button>';

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
            'title' => 'List of Production Routes',
            'footer' => [
                '<script src="' . base_url('js/MasterData/CommonData/ProductionRoutes/routes.js') . '"></script>',
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>',
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>'
            ]
        ];

        return view('MasterData/CommonData/ProductionRoutes/index', $data);
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
                'ProductionRoutes::saveData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $this->validasi->setRules([
                'data_name' => [
                    'label' => 'Production Routes Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ],
                'data_simbol' => [
                    'label' => 'Production Routes',
                    'rules' => 'required|min_length[1]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
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
                    'ProductionRoutes::saveData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $id = generate_uuid();
            $code = $this->masterModel->generateCode('m_routes', 'code', 'RTS-', 6);
            $name = trim(strip_tags($this->request->getPost('data_name')));
            $route = trim(strip_tags($this->request->getPost('data_simbol')));
            $remark = trim(strip_tags($this->request->getPost('data_remark')));

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => $name,
                'route' => $route,
                'remark' => $remark,
                'created_by' => $this->NIK,
            ];

            $insert = $this->routesModel->insert($data);

            $this->db->transCommit();

            if (!$insert) {
                logFile(
                    'error',
                    'Save error',
                    [
                        'message' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::saveData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to save a new production routes data');
            }

            logFile(
                'audit',
                'Production routes data saved successfully',
                [
                    'routes_id' => $id,
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::saveData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Production routes data has been saved');
        } catch (\Exception $e) {
            $this->db->transRollback();
            logFile(
                'error',
                'Error while saving new production routes data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::saveData',
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
                'ProductionRoutes::getData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Production routes token is not available in JSON data');
            }

            $token = $json_data['token'];
            $id = dekripsi($token);

            $getData = $this->routesModel->where('id', $id)->first();
            if (!$getData) {
                logFile(
                    'error',
                    'Production routes data not found',
                    [
                        'id' => $id,
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::getData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Production routes data not found');
            }

            $data = [
                'token' => enkripsi($id),
                'code' => $getData->code,
                'name' => $getData->name,
                'route' => $getData->route,
                'remark' => $getData->remark
            ];

            return pesan(ResponseInterface::HTTP_OK, 'Data retrieved', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Error while getting production routes data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::getData',
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
                'ProductionRoutes::updateData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();

        try {
            $this->validasi->setRules([
                'data_token' => [
                    'label' => 'Production routes token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'data_code' => [
                    'label' => 'Production routes code',
                    'rules' => 'required|min_length[3]|max_length[20]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ],
                'data_name' => [
                    'label' => 'Production routes name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                        'max_length' => '{field} cannot exceed {param} characters'
                    ]
                ],
                'data_simbol' => [
                    'label' => 'Production routes',
                    'rules' => 'required|min_length[1]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must have minimum {param} characters',
                    ]
                ]
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
            $route = trim($this->request->getPost('data_simbol'));
            $remark = trim($this->request->getPost('data_remark'));

            $data = [
                'name' => $name,
                'route' => $route,
                'remark' => $remark,
                'updated_by' => $this->NIK
            ];

            $update = $this->routesModel->update($id, $data);
            $this->db->transCommit();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Error while updating production routes data',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::updateData',
                );

                return pesan(
                    ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                    'Error while updating production routes data',
                );
            }

            if (!$update) {
                logFile(
                    'error',
                    'Error while updating production routes data',
                    [
                        'error' => $this->routesModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::updateData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Error while updating production routes data');
            }

            logFile(
                'audit',
                'Updated production routes data was successfully',
                [
                    'id' => $id,
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::updateData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Production routes data was successfully updated');
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
                'ProductionRoutes::updateData',
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
                    'route' => '/satuan/delete',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'ProductionRoutes::deleteData',
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
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Production routes token is not available in JSON data');
            }

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            $deleteData = $this->routesModel->delete($id);
            $this->db->transCommit();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Error while deleting production routes data',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::deleteData',
                );
            }

            if (!$deleteData) {
                logFile(
                    'error',
                    'Error while deleting production routes data',
                    [
                        'error' => $this->routesModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'ProductionRoutes::deleteData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Error while deleting production routes data');
            }

            logFile(
                'audit',
                'Deleted Production routes data was successfully',
                [
                    'id' => $id,
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::deleteData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Production routes data was successfully deleted');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Error during deleting Production routes data',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'ProductionRoutes::deleteData',
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
            $fileName = 'UoM Data ' . date('Y-m-d H:i:s');

            $headers = [
                'code',
                'name',
                'route',
                'remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, route, remark';
                return $this->masterModel->getChunkedData('m_routes', $offset, $limit, 'code', $column);
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
        $data = $this->routesModel->orderBy('code', 'asc')->findAll();

        return pesan(ResponseInterface::HTTP_OK, 'Data was successfully seeded', $data);
    }
}
