<?php

namespace App\Controllers\MasterData\CommonData\Customer;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\CommonData\Customer\CustomerModel;
use App\Models\DataTable\DataTableModel;
use App\Models\Master\MasterModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Config\Services;
use Config\Database;

class Customer extends BaseController
{
    protected $customerModel;
    protected $validasi;
    protected $db;
    protected $dataTable;
    protected $masterModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_customer';
        $column_order = [];
        $column_serach = [];
        $order = array('code' => 'asc');
        $this->dataTable = new DataTableModel(services::request(), $table, $column_order, $column_serach, $order);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $token = enkripsi($item->id);

            $row[] = '
                <a href="' . base_url() . 'customer/show/' . $token . '" class="nav-link text-decoration-none text-primary fw-bolder">' . $item->code . '</a>
            ';
            $row[] = $item->name;
            $row[] = $item->address;
            $row[] = ($item->email) ? dekripsi($item->email) : '';
            $row[] = ($item->phone) ? dekripsi($item->phone) : '';
            $row[] = $item->contact_person;
            $row[] = ($item->contact_person_phone) ? dekripsi($item->contact_person_phone) : '';
            $row[] = ($item->contact_person_email) ? dekripsi($item->contact_person_email) : '';
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . $token . '`)">
                    <i class="bi bi-x"></i>
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
            'title' => 'List of Customer',
            'footer' => [
                '<script src="' . base_url('js/MasterData/CommonData/Customer/customer.js') . '"></script>',
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>',
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"></script>'
            ]
        ];

        return view('MasterData/CommonData/Customer/index', $data);
    }

    function addData()
    {
        $data = [
            'title' => 'Add New Customer',
            'footer' => [
                '<script src="' . base_url('js/MasterData/CommonData/Customer/add.js') . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/Customer/add', $data);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Customer::saveData',
            );
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_name' => [
                    'label' => 'Customer Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters in length',
                        'max_length' => '{field} must not exceed {param} characters in length',
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
                    'Customer::saveData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $id = generate_uuid();
            $code = $this->masterModel->generateCode('m_customer', 'code', 'CUST-', 6);
            $name = $this->request->getPost('data_name');
            $address = $this->request->getPost('data_alamat');
            $phone = $this->request->getPost('data_phone');
            $email = $this->request->getPost('data_email');
            $contact_person = $this->request->getPost('data_cp');
            $email_cp = $this->request->getPost('data_cp_email');
            $phone_cp = $this->request->getPost('data_cp_phone');
            $remark = $this->request->getPost('data_remark');

            // Encrypt email and phone number
            $encrypted_phone = enkripsi($phone);
            $encrypted_email = enkripsi($email);
            $encrypted_email_cp = enkripsi($email_cp);
            $encrypted_phone_cp = enkripsi($phone_cp);

            // Hasing email and phone number
            $hashed_phone = phone_hash($phone);
            $hashed_email = email_hash($email);
            $hashed_email_cp = email_hash($email_cp);
            $hashed_phone_cp = phone_hash($phone_cp);

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => $name,
                'address' => $address,
                'email' => $encrypted_email,
                'email_hash' => $hashed_email,
                'phone' => $encrypted_phone,
                'phone_hash' => $hashed_phone,
                'contact_person' => $contact_person,
                'contact_person_phone' => $encrypted_phone_cp,
                'contact_person_phone_hash' => $hashed_phone_cp,
                'contact_person_email' => $encrypted_email_cp,
                'contact_person_email_hash' => $hashed_email_cp,
                'remark' => $remark,
                'created_by' => $this->NIK,
            ];

            $insert = $this->customerModel->insert($data);
            if (!$insert) {
                $this->db->transRollback();
                logFile(
                    'error',
                    'Failed to save a new customer data',
                    [
                        'message' => $this->customerModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'Customer::saveData',
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error');
            }

            logFile(
                'audit',
                'New customer data has been saved',
                [
                    'new_customer_id' => $id,
                    'NIK' => session('user_name')
                ],
                'Customer::saveData',
            );

            $this->db->transCommit();

            return pesan(ResponseInterface::HTTP_OK, 'New customer data has been saved');
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
                'Customer::saveData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function showData($token)
    {
        $id = dekripsi($token);

        $getData = $this->customerModel->where('id', $id)->first();

        $data = [
            'title' => "Edit Customer Data | " . $getData->name,
            'data' => $getData,
            'footer' => [
                '<script src="' . base_url('js/MasterData/CommonData/Customer/show.js') . '"></script>'
            ]
        ];

        return view('MasterData/CommonData/Customer/show', $data);
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/master-data/common-data/customer/update',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => $this->NIK
                ],
                'Customer::updateData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_token' => [
                    'label' => 'Customer Token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'data_name' => [
                    'label' => 'Customer Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least {param} characters in length',
                        'max_length' => '{field} must not exceed {param} characters in length',
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
                    'Customer::updateData',
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, $error_message);
            }

            $token = $this->request->getPost('data_token');
            $id = dekripsi($token);
            $code = $this->request->getPost('data_code');
            $name = $this->request->getPost('data_name');
            $address = $this->request->getPost('data_alamat');
            $phone = $this->request->getPost('data_phone');
            $email = $this->request->getPost('data_email');
            $contact_person = $this->request->getPost('data_cp');
            $email_cp = $this->request->getPost('data_cp_email');
            $phone_cp = $this->request->getPost('data_cp_phone');
            $remark = $this->request->getPost('data_remark');

            // Encrypt email and phone number
            $encrypted_phone = enkripsi($phone);
            $encrypted_email = enkripsi($email);
            $encrypted_email_cp = enkripsi($email_cp);
            $encrypted_phone_cp = enkripsi($phone_cp);

            // Hasing email and phone number
            $hashed_phone = phone_hash($phone);
            $hashed_email = email_hash($email);
            $hashed_email_cp = email_hash($email_cp);
            $hashed_phone_cp = phone_hash($phone_cp);

            $data = [
                'name' => $name,
                'address' => $address,
                'email' => $encrypted_email,
                'email_hash' => $hashed_email,
                'phone' => $encrypted_phone,
                'phone_hash' => $hashed_phone,
                'contact_person' => $contact_person,
                'contact_person_phone' => $encrypted_phone_cp,
                'contact_person_phone_hash' => $hashed_phone_cp,
                'contact_person_email' => $encrypted_email_cp,
                'contact_person_email_hash' => $hashed_email_cp,
                'remark' => $remark,
                'updated_by' => $this->NIK,
            ];

            $update = $this->customerModel->update($id, $data);

            if (!$update) {
                $this->db->transRollback();
                logFile(
                    'error',
                    'Unexpected error',
                    [
                        'error' => $this->customerModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'Customer::updateData',
                );
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error');
            }

            $this->db->transCommit();
            logFile(
                'audit',
                'Success update customer data',
                [
                    'id' => $id,
                    'data' => $data,
                    'NIK' => session('user_name')
                ],
                'Customer::updateData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Success update customer data');
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
                'Customer::updateData',
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
                    'route' => '/delete-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                ],
                'Customer::deleteData',
            );
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
            $id = dekripsi($token);

            $checkData = $this->customerModel->where('id', $id)->first();

            if (!$checkData) {
                logFile(
                    'error',
                    'Data not found',
                    [
                        'key' => $token,
                        'NIK' => session('user_name')
                    ],
                    'Customer::deleteData',
                );

                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'Data not found');
            }

            $update = $this->customerModel->delete($id);
            if (!$update) {
                logFile(
                    'error',
                    'Failed to delete customer data',
                    [
                        'error' => $this->customerModel->errors(),
                        'NIK' => session('user_name')
                    ],
                    'Customer::deleteData',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete customer data');
            }

            logFile(
                'audit',
                'Success delete customer data',
                [
                    'id' => $id,
                    'NIK' => session('user_name')
                ],
                'Customer::deleteData',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Success delete customer data');
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
                'Customer::deleteData',
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
                    'route' => '/customer/export-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'GET',
                    'NIK' => session('user_name')
                ],
                'Customer::exportData',
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $fileName = "customer_list" . date("Ymd_his") . 'xlsx';
            $headers = [
                'Code',
                'Name',
                'Address',
                'Email Address',
                'Phone Number',
                'Contact Person',
                'Contact Person Email Address',
                'Contact Person Phone Number',
                'Remark',
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, address, email, phone, contact_person, contact_person_phone, contact_person_email, remark';
                return $this->masterModel->getChunkedData('m_customer', $offset, $limit, 'code', $column);
            };

            return export_decrypted_data($fileName, $headers, ['email', 'phone', 'contact_person_phone', 'contact_person_email'], $dataCallback);
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
                'Customer::exportData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function prevData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer/prev-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Customer::prevData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['code'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Customer code is not available in JSON data');
            }

            $code = $json_data['code'];
            $getData = $this->customerModel->getPrevData($code);
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'You are at the first data');
            }

            return pesan(ResponseInterface::HTTP_OK, 'Success get data', [
                'token' => enkripsi($getData->id)
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
                    'NIK' => session('user_name')
                ],
                'Customer::prevData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function nextData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/customer/prev-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'Customer::prevData',
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['code'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Customer code is not available in JSON data');
            }

            $code = $json_data['code'];
            $getData = $this->customerModel->getNextData($code);
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_NOT_FOUND, 'You are at the last data');
            }

            return pesan(ResponseInterface::HTTP_OK, 'Success get data', [
                'token' => enkripsi($getData->id)
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
                    'NIK' => session('user_name')
                ],
                'Customer::nextData',
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }
}
