<?php

namespace App\Controllers\MasterData\ProjectSetup\Project;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\ProjectSetup\Project\ProjectModel;
use App\Models\MasterData\ProjectSetup\Project\ProjectDetailModel;
use App\Models\MasterData\CommonData\Material\MaterialModel;
use App\Models\MasterData\CommonData\Customer\CustomerModel;
use App\Models\MasterData\CommonData\ProductionRoutes\ProductionRoutesModel;
use App\Models\MasterData\APQPSetup\APQPLevel\APQPLevelModel;
use App\Models\MasterData\APQPSetup\APQPDocument\APQPDocumentModel;
use App\Models\MasterData\APQPSetup\APQPApprover\APQPApproverModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;
use Exception;

class Project extends BaseController
{
    protected $projectModel;
    protected $detailsModel;
    protected $materialModel;
    protected $customerModel;
    protected $routesModel;
    protected $masterModel;
    protected $levelModel;
    protected $approverModel;
    protected $documentModel;
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
        $this->levelModel = new APQPLevelModel();
        $this->approverModel = new APQPApproverModel();
        $this->documentModel = new APQPDocumentModel();
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

    function generateAPQP()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/project/generate_apqp',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Project::generateAPQP'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, "Request not allowed");
        }

        // $this->db->transStart();

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request is not a valid JSON data");
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Project token is not available on JSON request");
            }

            $token = $json_data['token'];
            $id_project = dekripsi($token);
            $data = [];

            // Check apakah sudah digenerate
            $checkApqp = $this->masterModel->checkData('m_project_apqp', 'id_project', $id_project);
            if ($checkApqp) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'APQP already generated');
            }

            // Get Material Project Details
            $getMaterialProject = $this->detailsModel->where('id_project', $id_project)->findAll();

            if (!$getMaterialProject) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'Project Details Not Found');
            }

            $getApqpLevel = $this->levelModel->orderBy('level', 'ASC')->findAll();
            if (!$getApqpLevel) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Level Not Found');
            }


            $getApqpApprover = $this->approverModel->orderBy('baris', 'ASC')->findAll();
            if (!$getApqpApprover) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Approver Not Found');
            }


            $getApqpDocument = $this->documentModel->orderBy('baris', 'ASC')->findAll();
            if (!$getApqpDocument) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Document Not Found');
            }

            $data_apqp = [];
            $data_approver = [];
            $data_document = [];

            // Looping material
            foreach ($getMaterialProject as $material) {
                // Looping APQP level
                foreach ($getApqpLevel as $apqpLevel) {
                    $data_apqp[] = [
                        'id' => generate_uuid(),
                        'id_project' => $id_project,
                        'id_material' => $material->id_material,
                        'id_apqp' => $apqpLevel->id,
                        'baris' => $apqpLevel->level,
                        'status' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->NIK,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];

                    // Looping APQP Approver
                    foreach ($getApqpApprover as $apqpApprover) {
                        $data_approver[] = [
                            'id' => generate_uuid(),
                            'id_project' => $id_project,
                            'id_material' => $material->id_material,
                            'baris' => $apqpApprover->baris,
                            'id_apqp' => $apqpLevel->id,
                            'id_approver' => $apqpApprover->approver,
                            'status' => '0',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->NIK,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }

                    // Looping APQP Document
                    foreach ($getApqpDocument as $apqpDoc) {
                        $data_document[] = [
                            'id' => generate_uuid(),
                            'id_project' => $id_project,
                            'id_material' => $material->id_material,
                            'id_apqp' => $apqpLevel->id,
                            'baris' => $apqpDoc->baris,
                            'id_document' => $apqpDoc->id,
                            'id_uploader' => $apqpDoc->uploader,
                            'status' => '0',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $this->NIK,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }
                }
            }

            $insert_apqp = $this->projectModel->insertApqp($data_apqp);
            if (!$insert_apqp) {
                logFile(
                    'error',
                    'Failed to insert project APQP data',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'Project::generateAPQP'
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to generate APQP');
            }

            $insert_approver = $this->projectModel->insertApqpApprover($data_approver);
            if (!$insert_approver) {
                logFile(
                    'error',
                    'Failed to insert project APQP approver',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'Project::generateAPQP'
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to generate APQP approver');
            }

            $insert_document = $this->projectModel->insertApqpDocument($data_document);
            if (!$insert_document) {
                logFile(
                    'error',
                    'Failed to insert project APQP document',
                    [
                        'error' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'Project::generateAPQP'
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to generate APQP document');
            }

            // $this->db->transComplete();

            // if ($this->db->transStatus() === false) {
            //     $this->db->transRollback();
            //     logFile(
            //         'error',
            //         'Failed to generate APQP',
            //         [
            //             'error' => $this->db->error(),
            //             'NIK' => $this->NIK
            //         ],
            //         'Project::generateAPQP'
            //     );
            //     throw new \Exception('Failed to generate APQP');
            //     return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to generate APQP');
            // }

            // $this->db->transCommit();
            logFile(
                'audit',
                'Generate APQP Success',
                [
                    'id_project' => $id_project,
                    'NIK' => $this->NIK
                ],
                'Project::generateAPQP'
            );
            return pesan(ResponseInterface::HTTP_OK, 'Success to generate APQP');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Project::generateAPQP'
            );
        }
    }

    function getApqp()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/project/get_apqp',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Project::getApqp'
            );
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Bad request');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON request');
            }

            if (!isset($json_data['id_project']) || empty($json_data['id_material'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Project token or material token is not available on JSON request');
            }

            $id_project = trim($json_data['id_project']);
            $id_material = trim($json_data['id_material']);

            $getData = $this->projectModel->getApqp($id_project, $id_material);
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP Data is not setup for this part no');
            }

            return pesan(ResponseInterface::HTTP_OK, 'Success to get APQP', $getData);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Project::getApqp'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to get APQP' . $e->getMessage());
        }
    }

    function getApprover()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/project/get_approver',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Project::getApprover'
            );
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Bad request');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON request');
            }

            if (!isset($json_data['id_project']) || empty($json_data['id_material'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Project token or material token is not available on JSON request');
            }

            $id_project = trim($json_data['id_project']);
            $id_material = trim($json_data['id_material']);

            $getData = $this->projectModel->getApprover($id_project, $id_material);
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'APQP approver data is not setup for this part no');
            }

            return pesan(ResponseInterface::HTTP_OK, 'Success to get Approver', $getData);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'Project::getApprover'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to get Approver' . $e->getMessage());
        }
    }
}
