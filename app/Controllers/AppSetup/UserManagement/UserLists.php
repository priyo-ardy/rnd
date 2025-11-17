<?php

namespace App\Controllers\AppSetup\UserManagement;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Auth\AuthModel;
use App\Models\Master\MasterModel;
use App\Models\DataTable\DataTableModel;
use Config\Services;
use Config\Database;

class UserLists extends BaseController
{
    protected $authModel;
    protected $masterModel;
    protected $dataTable;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();

        $table = 'm_user_auth';
        $column_order = ['user_name', 'full_name', 'email_hash', 'phone_hash', 'last_login', 'login_from'];
        $column_search = ['user_name', 'full_name', 'email_hash', 'phone_hash', 'last_login', 'login_from'];
        $order = array('user_name' => 'ASC');

        $this->dataTable = new DataTableModel(services::request(), $table, $column_order, $column_search, $order);
    }

    public function index()
    {
        $data = [
            'title' => "List of Users",
            'footer' => [
                '<script src="' . base_url() . 'js/AppSetup/UserManagement/UserLists/user.js' . '"></script>'
            ]
        ];

        return view('AppSetup/UserManagement/UserLists/index', $data);
    }

    function loadTable()
    {
        $lists = $this->dataTable->get_datatables();
        $data = [];

        foreach ($lists as $item) {
            $row = [];

            $row[] = '
                <a href="#" class="text-decoration-none text-primary fw-bolder" onclick="editUser(`' . enkripsi($item->user_id) . '`)">
                    ' . $item->user_name . '
                </a>
            ';
            $row[] = $item->full_name;
            $row[] = ($item->user_email) ? sensor_email(dekripsi($item->user_email)) : '';
            $row[] = ($item->user_phone) ? sensor_phone_number(dekripsi($item->user_phone)) : '';
            $row[] = '';
            $row[] = $item->last_login;
            $row[] = $item->login_from;
            $row[] = $item->remark;
            $row[] = '
                <button type="button" title="Click to delete this data" class="text-danger btn text-danger shadow-none btn-sm rounded-0 fw-bolder" onclick="deleteData(`' . enkripsi($item->user_id) . '`)">
                    <i class="fa-solid fa-x"></i>
                </button>
                <button type="button" title="Change Password" class="text-primary btn shadow-none btn-sm rounded-0 fw-bolder" onclick="changePassword(`' . enkripsi($item->user_id) . '`)">
                    <i class="fa-solid fa-key"></i>
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

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request methodt not allowed',
                [
                    'route' => '/user-lists/save',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => $this->NIK
                ],
                'UserLists::saveData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        $this->db->transStart();
        try {
            $rules = [
                'data_username' => [
                    'label' => 'Username',
                    'rules' => 'required|is_unique[m_user_auth.user_name]|min_length[3]|max_length[25]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_unique' => 'Username already exists',
                        'min_length' => 'Username must be at least 3 characters',
                        'max_length' => 'Username must be at most 25 characters'
                    ]
                ],
                'data_fullname' => [
                    'label' => 'Full Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => 'Full Name is required',
                        'min_length' => 'Full Name must be at least 3 characters',
                        'max_length' => 'Full Name must be at most 150 characters'
                    ]
                ],
                'data_email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid',
                        'min_length' => 'Email must be at least 3 characters',
                        'max_length' => 'Email must be at most 150 characters'
                    ]
                ],
                'data_phone' => [
                    'label' => 'Phone Number',
                    'rules' => 'required|min_length[3]|max_length[20]',
                    'errors' => [
                        'required' => 'Phone Number is required',
                        'min_length' => 'Phone Number must be at least 3 characters',
                        'max_length' => 'Phone Number must be at most 20 characters'
                    ]
                ],
                'data_level' => [
                    'label' => 'User Level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'User level is required'
                    ]
                ],
                'data_password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 3 characters',
                        'max_length' => 'Password must be at most 50 characters'
                    ]
                ]
            ];

            $this->validasi->setRules($rules);

            if ($this->validasi->withRequest($this->request)->run() == FALSE) {
                $error_message = implode('<br>', $this->validasi->getErrors());

                logFile(
                    'error',
                    'Validation error occured',
                    [
                        'message' => $this->validasi->getErrors(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::saveData'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation error : <br>" . $error_message);
            }

            $user_id = generate_uuid();
            $user_name = trim($this->request->getPost('data_username'));
            $full_name = trim($this->request->getPost('data_fullname'));
            $email = trim($this->request->getPost('data_email'));
            $phone = trim($this->request->getPost('data_phone'));
            $level = trim($this->request->getPost('data_level'));
            $password = trim($this->request->getPost('data_password'));

            // Enkripsi
            $encrypt_email = enkripsi($email);
            $encrypt_phone = enkripsi($phone);
            $encrypt_password = password_hash($password, PASSWORD_DEFAULT);

            // Hashing
            $hash_email = email_hash($email);
            $hash_phone = phone_hash($phone);
            $error_message = [];

            $cek_email = $this->authModel->where('email_hash', $hash_email)->first();
            if ($cek_email) {
                $error_message[] = 'Email already registered';
            }

            $cek_phone = $this->authModel->where('phone_hash', $hash_phone)->first();
            if ($cek_phone) {
                $error_message[] = 'Phone number already registered';
            }

            if (count($error_message) > 0) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, implode('<br>', $error_message));
            }

            $data = [
                'user_id' => $user_id,
                'user_name' => $user_name,
                'user_password' => $encrypt_password,
                'full_name' => $full_name,
                'user_email' => $encrypt_email,
                'email_hash' => $hash_email,
                'user_phone' => $encrypt_phone,
                'phone_hash' => $hash_phone,
                'user_status' => '1',
                'user_level' => $level,
                'user_image' => 'default.png',
                'created_by' => $this->NIK
            ];

            $insert = $this->authModel->insert($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                logFile(
                    'error',
                    'Save failed',
                    [
                        'message' => $this->db->error(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::saveData'
                );
            }

            if (!$insert) {
                logFile(
                    'error',
                    'Save failed',
                    [
                        'message' => $this->authModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::saveData'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Save failed');
            }

            logFile(
                'audit',
                'Save success',
                [
                    'new_user_id' => $user_id,
                    'NIK' => $this->NIK
                ],
                'UserLists::saveData'
            );

            return pesan(ResponseInterface::HTTP_OK, 'Save success');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'UserLists::saveData'
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
                    'route' => '/user-lists/get-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UserLists::getData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON request');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User token is not available in JSON request');
            }

            $token = trim($json_data['token']);
            $user_id = dekripsi($token);

            $getUser = $this->authModel->where('user_id', $user_id)->first();
            if (!$getUser) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User not found');
            }

            $data = [
                'user_name' => $getUser->user_name,
                'full_name' => $getUser->full_name,
                'user_email' => dekripsi($getUser->user_email),
                'user_phone' => dekripsi($getUser->user_phone),
                'user_level' => $getUser->user_level
            ];

            return pesan(ResponseInterface::HTTP_OK, 'Success', $data);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'UserLists::getData'
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
                    'route' => '/user-lists/update-data',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UserLists::updateData'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $rules = [
                'data_username' => [
                    'label' => 'Username',
                    'rules' => 'required|min_length[3]|max_length[25]',
                    'errors' => [
                        'required' => 'Username is required',
                        'min_length' => 'Username must be at least 3 characters',
                        'max_length' => 'Username must be at most 25 characters'
                    ]
                ],
                'data_fullname' => [
                    'label' => 'Full Name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => 'Full Name is required',
                        'min_length' => 'Full Name must be at least 3 characters',
                        'max_length' => 'Full Name must be at most 150 characters'
                    ]
                ],
                'data_email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Email is not valid',
                        'min_length' => 'Email must be at least 3 characters',
                        'max_length' => 'Email must be at most 150 characters'
                    ]
                ],
                'data_phone' => [
                    'label' => 'Phone Number',
                    'rules' => 'required|min_length[3]|max_length[20]',
                    'errors' => [
                        'required' => 'Phone Number is required',
                        'min_length' => 'Phone Number must be at least 3 characters',
                        'max_length' => 'Phone Number must be at most 20 characters'
                    ]
                ],
                'data_level' => [
                    'label' => 'User Level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'User level is required'
                    ]
                ],
                'data_token' => [
                    'label' => 'User Token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'User token is required',
                    ]
                ]
            ];

            $this->validasi->setRules($rules);
            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode("<br>", $this->validasi->getErrors());

                logFile(
                    'error',
                    'Validation error',
                    [
                        'message' => $this->validasi->getErrors(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::updateData'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation error " . $error_message);
            }

            $token = trim($this->request->getPost('data_token'));
            $user_id = dekripsi($token);
            $user_name = trim($this->request->getPost('data_username'));
            $full_name = trim($this->request->getPost('data_fullname'));
            $email = trim($this->request->getPost('data_email'));
            $phone = trim($this->request->getPost('data_phone'));
            $level = trim($this->request->getPost('data_level'));
            $password = trim($this->request->getPost('data_password'));

            // Enkripsi
            $encrypt_email = enkripsi($email);
            $encrypt_phone = enkripsi($phone);
            $encrypt_password = password_hash($password, PASSWORD_DEFAULT);

            // Hashing
            $hash_email = email_hash($email);
            $hash_phone = phone_hash($phone);
            $error_message = [];

            $getUser = $this->authModel->where('user_id', $user_id)->first();
            if (!$getUser) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User not found');
            }

            if ($getUser->user_name != $user_name) {
                $cek_username = $this->authModel->where('user_name', $user_name)->first();
                if ($cek_username) {
                    $error_message[] = 'Username already exists';
                }

                $cek_email = $this->authModel->where('email_hash', $hash_email)->first();
                if ($cek_email) {
                    $error_message[] = 'Email already exists';
                }

                $cek_phone = $this->authModel->where('phone_hash', $hash_phone)->first();
                if ($cek_phone) {
                    $error_message[] = 'Phone number already exists';
                }
            }

            if (count($error_message) > 0) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, implode("<br>", $error_message));
            }

            $data = [
                'user_name' => $user_name,
                'user_password' => $encrypt_password,
                'full_name' => $full_name,
                'user_email' => $encrypt_email,
                'email_hash' => $hash_email,
                'user_phone' => $encrypt_phone,
                'phone_hash' => $hash_phone,
                'user_status' => '1',
                'user_level' => $level,
                'user_image' => 'default.png',
                'created_by' => $this->NIK
            ];

            $update = $this->authModel->update($user_id, $data);
            if (!$update) {
                logFile(
                    'error',
                    'Failed to update user data',
                    [
                        'message' => $this->authModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::updateData'
                );
            }

            logFile(
                'audit',
                'User data updated',
                [
                    'user_id' => $user_id,
                    'NIK' => $this->NIK
                ],
                'UserLists::updateData'
            );
            return pesan(ResponseInterface::HTTP_OK, 'User data updated successfully');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'UserLists::updateData'
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
                    'route' => '/user-lists/delete',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UserLists::deleteData'
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User token is not available in JSON data');
            }

            $token = $json_data['token'];
            $user_id = dekripsi($token);

            $getUser = $this->authModel->where('user_id', $user_id)->first();
            if (!$getUser) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User not found');
            }

            $delete = $this->authModel->delete($user_id);
            if (!$delete) {
                logFile(
                    'error',
                    'Failed to delete user data',
                    [
                        'message' => $this->authModel->errors(),
                        'NIK' => $this->NIK
                    ],
                    'UserLists::deleteData'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete user data');
            }

            logFile(
                'audit',
                'User data deleted',
                [
                    'user_id' => $user_id,
                    'NIK' => $this->NIK
                ],
                'UserLists::deleteData'
            );

            return pesan(ResponseInterface::HTTP_OK, 'User data deleted successfully');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'UserLists::deleteData'
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
            $fileName = "users_list" . date("Ymd_his") . 'xlsx';
            $headers = [
                'User Name',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Last Login',
                'last Login From',
                'Remark',
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'user_name, full_name, user_email, user_phone, last_login, login_from, remark';
                return $this->masterModel->getChunkedData('m_user_auth', $offset, $limit, 'user_name', $column);
            };

            return export_decrypted_data($fileName, $headers, ['user_email', 'user_phone'], $dataCallback);
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

    function changePassword()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/user-lists/change-password',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'UserLists::changePassword'
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $rules = [
                'user_token' => [
                    'label' => 'User Token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'User token is required'
                    ]
                ],
                'new_password' => [
                    'label' => 'New Password',
                    'rules' => 'required|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => 'New password is required',
                        'min_length' => 'New password must be at least 3 characters',
                        'max_length' => 'New password must not exceed 50 characters'
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
                        'NIK' => $this->NIK
                    ],
                    'UserLists::changePassword'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation error <br>" . $error_message);
            }

            $user_token = trim($this->request->getPost('user_token'));
            $user_id = dekripsi($user_token);
            $new_password = trim($this->request->getPost('new_password'));
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $data = [
                'user_password' => $password_hash
            ];

            $change = $this->authModel->update($user_id, $data);

            if ($change) {
                return pesan(ResponseInterface::HTTP_OK, 'Password has been changed');
            } else {
                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to change password');
            }
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error occured',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => $this->NIK
                ],
                'UserLists::changePassword'
            );
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error occured' . $e->getMessage());
        }
    }

    function dataSeeder()
    {
        $get = $this->authModel->orderBy('user_name', 'asc')->findAll();

        return pesan(ResponseInterface::HTTP_OK, 'Data was successfully seeded', $get);
    }
}
