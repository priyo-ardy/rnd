<?php

namespace App\Services;

use CodeIgniter\HTTP\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\HTTP\ResponseInterface;
use PHPUnit\Framework\Constraint\Callback;

class ExcelExportService
{
    protected $spreadsheet;
    protected $response;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->response = service('response');
    }

    public function exportLargeData(string $filename, array $headers, callable $dataCallback, int $chunkSize = 500): ResponseInterface
    {
        try {
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->fromArray($headers, null, 'A1');

            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');

            $offset = 0;
            $rowNumber = 2;

            while (true) {
                $data = $dataCallback($offset, $chunkSize);

                if (empty($data)) {
                    break;
                }

                $output = [];
                foreach ($data as $row) {
                    $output[] = $row;
                }

                $sheet->fromArray($output, null, 'A' . $rowNumber);
                $rowNumber += count($data);

                $offset += $chunkSize;
            }

            $writer = new Xlsx($this->spreadsheet);
            ob_start();
            $writer->save('php://output');
            $output = ob_get_clean();

            return $this->response->setBody($output);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Excel Export Error',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')

                ]
            );

            log_message('error', 'Excel Export Error: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function quickExport(string $filename, array $headers, array $data): ResponseInterface
    {
        try {
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->fromArray($headers, null, 'A1');
            $sheet->fromArray($data, null, 'A2');

            $writer = new Xlsx($this->spreadsheet);
            ob_start();
            $writer->save('php://output');
            $output = ob_get_clean();

            return $this->response
                ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
                ->setBody($output);
        } catch (\Exception $e) {
            log_message('error', 'Quick Export Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => $e->getMessage()
            ]);
        }
    }

    function exportDecryptedData(string $filename, array $headers, callable $dataCallback, int $chunkSize = 500, array $decyptedColumns = []): ResponseInterface
    {
        try {
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->fromArray($headers, null, 'A1');

            $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
            $this->response->setHeader('Cache-Control', 'max-age=0');

            $offset = 0;
            $rowNumber = 2;

            while (true) {
                $data = $dataCallback($offset, $chunkSize);

                if (empty($data)) {
                    break;
                }

                $output = [];
                foreach ($data as $row) {
                    // Jika $row adalah array asosiatif (misalnya ['nama' => 'John', 'password' => 'encrypted'])
                    if (!empty($decyptedColumns)) {
                        foreach ($decyptedColumns as $column) {
                            if (isset($row[$column])) {
                                $row[$column] = dekripsi($row[$column]);  // Panggil fungsi dekripsi
                            }
                        }
                    }
                    $output[] = $row;
                }

                $sheet->fromArray($output, null, 'A' . $rowNumber);
                $rowNumber += count($data);

                $offset += $chunkSize;
            }

            $writer = new Xlsx($this->spreadsheet);
            ob_start();
            $writer->save('php://output');
            $output = ob_get_clean();

            return $this->response->setBody($output);
        } catch (\Exception $e) {
            logFile(
                'error',
                'Excel Export Error',
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'NIK' => session('user_name')
                ],
                'ExcelExportService::exportDecryptedData'
            );

            log_message('error', 'Excel Export Error: ' . $e->getMessage());

            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'error' => $e->getMessage()
                ]);
        }
    }

    function quickExportDecrypted(string $filename, array $headers, array $data, array $decyptedColumns): ResponseInterface
    {
        try {
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->fromArray($headers, null, 'A1');
            // Dekripsi kolom tertentu sebelum export
            $processedData = [];
            foreach ($data as $row) {
                if (!empty($decryptedColumns)) {
                    foreach ($decryptedColumns as $column) {
                        if (isset($row[$column])) {
                            $row[$column] = dekripsi($row[$column]);  // Panggil fungsi dekripsi
                        }
                    }
                }
                $processedData[] = $row;
            }
            $sheet->fromArray($processedData, null, 'A2');
            $writer = new Xlsx($this->spreadsheet);
            ob_start();
            $writer->save('php://output');
            $output = ob_get_clean();
            return $this->response
                ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
                ->setBody($output);
        } catch (\Exception $e) {
            log_message('error', 'Quick Export Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => $e->getMessage()
            ]);
        }
    }
}
