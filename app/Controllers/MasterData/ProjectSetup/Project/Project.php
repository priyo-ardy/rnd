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
            'material_list' => $this->materialModel->orderBy('code', 'ASC')->findAll(),
            'footer' => [
                '<script src="' . base_url() . 'js/MasterData/ProjectSetup/Project/add.js' . '"></script>'
            ]
        ];

        return view('MasterData/ProjectSetup/Project/add', $data);
    }

    function saveData() {}
}
