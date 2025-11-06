<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Auth\AuthModel;
use Config\Services;
use Config\Database;

class Auth extends BaseController
{
    protected $authModel;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();
    }

    public function index()
    {
        return view('Auth/index');
    }

    public function processLogin()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request methodt not allowed',
                [
                    'route' => '/login',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                ],
                'Auth::processLogin'
            );
        }

        try {
            $rules = [
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required',
                    ]
                ],
            ];

            $this->validasi->setRules($rules);
            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode(', ', $this->validasi->getErrors());
                logFile(
                    'security',
                    'Validation error',
                    [
                        'error' => $this->validasi->getErrors(),
                    ],
                    'Auth::processLogin'
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid Data', $error_message);
            }

            $user_name = $this->request->getPost('username');
            $user_password = $this->request->getPost('password');

            $getData = $this->authModel->where('user_name', $user_name)->first();
            if (!$getData) {
                logFile(
                    'error',
                    'User not found',
                    [
                        'keywords' => $user_name
                    ],
                    'Auth::processLogin'
                );

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'User not found or not registered');
            }

            if ($getData->attempts >= 5) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Account is locked');
            }

            if (!password_verify($user_password, $getData->user_password)) {
                logFile(
                    'error',
                    'Password not match',
                    [
                        'keywords' => $user_name
                    ],
                    'Auth::processLogin'
                );

                $attempt = $getData->attempts + 1;
                $updateAttempt = $this->authModel->update($getData->user_id, ['attempts' => $attempt]);
                if (!$updateAttempt) {
                    logFile(
                        'error',
                        'Failed to update login attempts',
                        [
                            'message' => $this->authModel->errors(),
                        ],
                        'Auth::processLogin'
                    );

                    return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed processing your request');
                }

                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid password, attemp ' . $attempt);
            }

            if ($getData->user_status == 0) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Account is inactive');
            }

            $session_data = [
                'logged' => true,
                'user_id' => enkripsi($getData->user_id),
                'user_name' => $getData->user_name,
                'full_name' => $getData->full_name,
                'user_image' => ($getData->user_image == null) ? 'default.png' : $getData->user_image,
            ];

            $update_login = $this->authModel->update($getData->user_id, ['last_login' => date('Y-m-d H:i:s'), 'login_from' => $this->request->getIPAddress(), 'user_agent' => $this->request->getUserAgent()]);
            if (!$update_login) {
                logFile(
                    'error',
                    'Failed to update last login',
                    [
                        'message' => $this->authModel->errors(),
                    ],
                    'Auth::processLogin'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed processing your request');
            }

            session()->set($session_data);
            return pesan(ResponseInterface::HTTP_OK, 'Login successfully');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
                'Auth::processLogin'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected Error ' . $e->getMessage());
        }
    }

    function forgotPassword()
    {
        return view('Auth/forgot');
    }
}
