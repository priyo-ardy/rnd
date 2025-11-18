<?php

namespace App\Controllers\MasterData\APQPSetup\APQPDocument;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MasterData\APQPSetup\APQPDocument\APQPDocumentModel;
use App\Models\Master\MasterModel;
use Config\Database;
use Config\Services;

class APQPDocument extends BaseController
{
    protected $documentModel;
    protected $masterModel;
    protected $validasi;
    protected $db;

    public function __construct()
    {
        $this->documentModel = new APQPDocumentModel();
        $this->masterModel = new MasterModel();
        $this->validasi = Services::validation();
        $this->db = Database::connect();
    }
    function getDocumentList()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-document/get_document_list',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPDocument::getDocumentList'
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
            $id_apqp = dekripsi($token);

            $getDocumentList = $this->documentModel->getDocumentList($id_apqp);

            return pesan(ResponseInterface::HTTP_OK, 'Success', $getDocumentList);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPDocument::getDocumentList'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function saveDocumentList()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-document/save_document_list',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPDocument::saveDocumentList'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $rules = [
                'apqp_document_token' => [
                    'label' => 'APQP Token',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'nama_dokumen.*' => [
                    'label' => 'Document name',
                    'rules' => 'required|min_length[3]|max_length[150]',
                    'errors' => [
                        'required' => '{field} is required',
                        'min_length' => '{field} must be at least 3 characters',
                        'max_length' => '{field} must be at most 150 characters'
                    ]
                ],
                'uploader.*' => [
                    'label' => 'Uploader',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
                    ]
                ],
                'document_level.*' => [
                    'label' => 'Document level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} is required'
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
                    'APQPDocument::saveDocumentList',
                );
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Validation error : <br>" . $error_message);
            }

            $token = trim($this->request->getPost('apqp_document_token'));
            $id_apqp = dekripsi($token);
            $nama_dokumen = $this->request->getPost('nama_dokumen');
            $uploader = $this->request->getPost('uploader');
            $document_level = $this->request->getPost('document_level');

            if (count($nama_dokumen) == 0 || count($uploader) == 0) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Data is not available');
            }

            $baris = $this->documentModel->getLastRow($id_apqp);
            $data = [];

            for ($i = 0; $i < count($nama_dokumen); $i++) {
                $data[] = [
                    'id' => generate_uuid(),
                    'id_apqp' => $id_apqp,
                    'baris' => $baris,
                    'nama_dokumen' => trim($nama_dokumen[$i]),
                    'level_dokumen' => $document_level[$i],
                    'uploader' => $uploader[$i],
                    'created_by' => $this->NIK
                ];

                $baris++;
            }

            $insert = $this->documentModel->insertBatch($data);
            if (!$insert) {
                logFile(
                    'error',
                    'Failed to insert data',
                    [
                        'message' => $this->documentModel->getLastQuery(),
                        'NIK' => $this->NIK
                    ],
                    'APQPDocument::saveDocumentList',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to insert data : <br>' . implode('<br>', $this->documentModel->getLastQuery()));
            }

            logFile(
                'audit',
                'Insert data success',
                [
                    'data' => $data,
                    'NIK' => $this->NIK
                ],
                'APQPDocument::saveDocumentList',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Data has been saved');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPDocument::saveDocumentList'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function deleteDocument()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-document/delete-document',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPDocument::deleteDocument'
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

            $id = $json_data['token'];
            $getData = $this->documentModel->where('id', $id)->first();
            if (!$getData) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Data not found');
            }

            $delete = $this->documentModel->delete($id, true);

            if (!$delete) {
                logFile(
                    'error',
                    'Failed to delete data',
                    [
                        'message' => $this->documentModel->getLastQuery(),
                        'NIK' => $this->NIK
                    ],
                    'APQPDocument::deleteDocument',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to delete data : <br>' . implode('<br>', $this->documentModel->getLastQuery()));
            }

            logFile(
                'audit',
                'Delete data success',
                [
                    'data' => $getData,
                    'NIK' => $this->NIK
                ],
                'APQPDocument::deleteDocument',
            );

            return pesan(ResponseInterface::HTTP_OK, 'Data has been deleted');
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPDocument::deleteDocument'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function updateDocument()
    {
        if ($this->request->getMethod() !== 'POST') {
            logFile(
                'security',
                'Request method not allowed',
                [
                    'route' => '/apqp-document/update-document',
                    'method' => $this->request->getMethod(),
                    'expected' => 'POST',
                    'NIK' => session('user_name')
                ],
                'APQPDocument::updateDocument'
            );

            return pesan(ResponseInterface::HTTP_METHOD_NOT_ALLOWED, 'Request method not allowed');
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Invalid JSON data');
            }

            if (!isset($json_data['token']) || !isset($json_data['nama_dokumen']) || !isset($json_data['nama_dokumen']) || !isset($json_data['uploader']) || !isset($json_data['level'])) {
                return pesan(ResponseInterface::HTTP_BAD_REQUEST, 'Token, document name and uploader is not available in JSON data');
            }

            $id = trim($json_data['token']);
            $nama_dokumen = trim($json_data['nama_dokumen']);
            $uploader = trim($json_data['uploader']);
            $level = trim($json_data['level']);

            $data = [
                'nama_dokumen' => trim($nama_dokumen),
                'uploader' => trim($uploader),
                'level_dokumen' => trim($level),
                'updated_by' => $this->NIK,
            ];

            $update = $this->documentModel->update($id, $data);
            if (!$update) {
                logFile(
                    'error',
                    'Failed to update data',
                    [
                        'message' => $this->documentModel->getLastQuery(),
                        'NIK' => $this->NIK
                    ],
                    'APQPDocument::updateDocument',
                );

                return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Failed to update data : <br>' . implode('<br>', $this->documentModel->getLastQuery()));
            }

            logFile(
                'audit',
                'Update data success',
                [
                    'data' => $data,
                    'id' => $id,
                    'NIK' => $this->NIK
                ],
                'APQPDocument::updateDocument',
            );

            $getData = $this->documentModel->getDocumentData($id);
            return pesan(ResponseInterface::HTTP_OK, 'Data has been updated', $getData);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Unexpected error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'NIK' => session('user_name')
                ],
                'APQPDocument::updateDocument'
            );

            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected error' . $e->getMessage());
        }
    }

    function seedData()
    {
        $get = $this->documentModel->orderBy('id_apqp', 'asc')->orderBy('baris', 'asc')->findAll();

        return pesan(ResponseInterface::HTTP_OK, 'Data was successfully seeded', $get);
    }
}
