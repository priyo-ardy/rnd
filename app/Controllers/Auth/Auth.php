<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Auth\AuthModel;
use App\Models\Queue\EmailQueueModel;
use App\Services\EmailQueueService;
use Config\Services;
use Config\Database;

class Auth extends BaseController
{
    protected $authModel;
    protected $validasi;
    protected $emailModel;
    protected $emailService;
    protected $db;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->validasi = Services::validation();
        $this->emailModel = new EmailQueueModel();
        $this->emailService = new EmailQueueService();
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

    function resetPassword()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/reset',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                ],
                'Auth::resetPassword'
            );
            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $rules = [
                'email_address' => [
                    'label' => 'Email Address',
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => '{field} is required',
                        'valid_email' => '{field} must be valid email address',
                    ]
                ]
            ];

            $this->validasi->setRules($rules);

            if (!$this->validasi->withRequest($this->request)->run()) {
                $error_message = implode(', ', $this->validasi->getErrors());

                logFile(
                    'error',
                    'Validation error',
                    [
                        'message' => $this->validasi->getErrors(),
                    ],
                    'Auth::resetPassword'
                );

                return pesan(ResponseInterface::HTTP_BAD_GATEWAY, $error_message);
            }

            $email_address = $this->request->getPost('email_address');
            $hash = email_hash($email_address);


            $checkEmail = $this->authModel->checkEmail($hash);
            if (!$checkEmail) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Email not registered');
            }

            $email = dekripsi($checkEmail->user_email);

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $random_password = '';
            for ($i = 0; $i < 8; $i++) {
                $random_password .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $new_password = password_hash($random_password, PASSWORD_DEFAULT);

            $data_password = [
                'user_password' => $new_password,
                'remark' => 'New password generated by system'
            ];

            $update_new_password = $this->authModel->update($checkEmail->user_id, $data_password);
            if (!$update_new_password) {
                logFile(
                    'error',
                    'Failed to update new password',
                    [
                        'message' => $this->authModel->errors(),
                    ],
                    'Auth::resetPassword'
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to process your request');
            }

            logFile(
                'audit',
                'password was reset successfully',
                [
                    'email' => $checkEmail->user_email,
                    'password' => $new_password,
                ],
                'Auth::resetPassword'
            );

            $data = [
                'new_password' => $random_password
            ];

            $body = view('Auth/reset', $data);

            $this->emailService->queueEmail($email, 'Application New Password', $body);

            return pesan(ResponseInterface::HTTP_OK, "Password has been reset successfully, please check your email to get a new password");
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
                'Auth::resetPassword'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, "Unexpected Error " . $e->getMessage());
        }
    }
}
