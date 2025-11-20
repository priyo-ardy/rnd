<?php

namespace App\Controllers\MasterData\ProjectSetup\Project;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\ProjectSetup\Project\ProjectModel;
use App\Models\MasterData\ProjectSetup\Project\ProjectDetailModel;
use App\Models\MasterData\CommonData\Material\MaterialModel;
use App\Models\MasterData\CommonData\Customer\CustomerModel;
use App\Models\MasterData\CommonData\ProductionRoutes\ProductionRoutesModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class Project extends BaseController
{
    protected $projectModel;
    protected $detailsModel;
    protected $materialModel;
    protected $customerModel;
    protected $routesModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->detailsModel = new ProjectDetailModel();
        $this->materialModel = new MaterialModel();
        $this->customerModel = new CustomerModel();
        $this->routesModel = new ProductionRoutesModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => "Project List",
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/ProjectSetup/Project/project.js' . '"></script>'
            ]
        ];

        return view('MasterData/ProjectSetup/Project/index', $data);
    }

    function addData()
    {
        $data = [
            'title' => "Create a New Project",
            'customer_list' => $this->customerModel->orderBy('name', 'ASC')->findAll(),
            'material_list' => $this->materialModel->where('kategori', 'd7e6cc88-39c0-4fd7-8acc-1c545108fcb2')->orderBy('code', 'ASC')->findAll(),
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/ProjectSetup/Project/add.js' . '"></script>'
            ]
        ];

        return view('MasterData/ProjectSetup/Project/add', $data);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/project/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Project::saveData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_code' => [
                    'label' => 'Project code',
                    'rules' => 'required|is_unique[m_project_header.code]|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => '{field} is required',
                        'is_unique' => '{field} already exists',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be at most {param} characters',
                    ]
                ],
                'data_name' => [
                    'label' => 'Project name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters',
                        'max_length' => '{field} must be at most {param} characters',
                    ]
                ],
                'data_type' => [
                    'label' => 'Project type',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'data_customer' => [
                    'label' => "Customer",
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'part_no.*' => [
                    'label' => 'Part number',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'due_date.*'  => [
                    'label' => 'Due date',
                    'rules' => 'required|valid_date',
                    'errors' => [
                        'required' => '{field} is required',
                        'valid_date' => '{field} must be a valid date',
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
                    'Project::saveData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation failed : " . $error_message);
            }

            // Header Data
            $id_header = generate_uuid();
            $code = trim($this->request->getPost('data_code'));
            $name = trim($this->request->getPost('data_name'));
            $type = trim($this->request->getPost('data_type'));
            $customer = trim($this->request->getPost('data_customer'));
            $remark = trim($this->request->getPost('data_remark'));

            // Details Data
            $part_no = $this->request->getPost('part_no');
            $part_no = array_unique($part_no);
            $part_no = array_values($part_no);
            $due_date = $this->request->getPost('due_date');
            $keterangan = $this->request->getPost('remark');
            $data_details = [];

            for ($i = 0; $i < count($part_no); $i++) {
                $data_details[] = [
                    'id' => generate_uuid(),
                    'id_project' => $id_header,
                    'id_material' => $part_no[$i],
                    'due_date' => $due_date[$i],
                    'status' => '0',
                    'remark' => $keterangan[$i],
                    'created_by' => $this->NIK,
                ];
            }

            // Insert header
            $data_header = [
                'id' => $id_header,
                'code' => strtoupper($code),
                'name' => ucwords($name),
                'project_type' => $type,
                'status' => '0',
                'customer' => $customer,
                'remark' => $remark,
                'created_by' => $this->NIK,
            ];

            $insert_header = $this->projectModel->insert($data_header);
            if (!$insert_header) {
                logFile(
                    'error',
                    "failed to save project header data",
                    [
                        'message' => $this->projectModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'Project::saveData',
                );

                throw new \Exception('Failed to save project header data');
            }

            $insert_details = $this->detailsModel->insertBatch($data_details);
            if (!$insert_details) {
                logFile(
                    'error',
                    'Failed to save project details data',
                    [
                        'message' => $this->detailsModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'Project::saveData',
                );

                throw new \Exception('Failed to save project details data');
            }


            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Failed to save data',
                    [
                        'message' => $this->db->getLastQuery(),
                        'NIK' => $this->NIK
                    ],
                    'Project::saveData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Failed to save data');
            }

            logFile(
                'audit',
                'Project data has been successfully saved',
                [
                    'project_id' => $id_header,
                    'NIK' => $this->NIK
                ],
                'Project::saveData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Project data has been successfully saved', [
                'token' => enkripsi($id_header)
            ]);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [],
                'Project::saveData',
            );

            return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Unexpected error : <br>' . $e->getMessage() . "On File : " . $e->getFile() . " On Line : " . $e->getLine());
        }
    }

    function showData($token)
    {
        $id_project = dekripsi($token);
        $data_header = $this->projectModel->where('id', $id_project)->first();
        $data_details = $this->detailsModel->getProjectDetails($id_project);

        $data = [
            'title' => "Project Details",
            'data_header' => $data_header,
            'data_details' => $data_details,
            'material_list' => $this->materialModel->where('kategori', 'd7e6cc88-39c0-4fd7-8acc-1c545108fcb2')->orderBy('code', 'ASC')->findAll(),
            'customer_list' => $this->customerModel->orderBy('name', 'ASC')->findAll(),
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/ProjectSetup/Project/edit.js' . '"></script>'
            ]
        ];

        return view('MasterData/ProjectSetup/Project/show', $data);
    }
}
