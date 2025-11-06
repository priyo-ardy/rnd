<?php

use App\Services\ExcelExportService;

if (!function_exists('export_to_excel')) {
    function export_to_excel(string $filename, array $headers, $data, ?int $chunkSize = null)
    {
        $excelService = new ExcelExportService();

        if (is_callable($data)) {
            if ($chunkSize === null) {
                $chunkSize = 5000;
            }

            return $excelService->exportLargeData($filename, $headers, $data, $chunkSize);
        }

        if (is_array($data)) {
            return $excelService->quickExport($filename, $headers, $data);
        }

        throw new InvalidArgumentException('Data must be either callabel or array');
    }

    function export_decrypted_data(string $filename, array $headers,  array $decyptedColumns = [], $data, ?int $chunkSize = null,)
    {
        $excelService = new ExcelExportService();

        if (is_callable($data)) {
            if ($chunkSize === null) {
                $chunkSize = 5000;
            }

            return $excelService->exportDecryptedData($filename, $headers, $data, $chunkSize, $decyptedColumns);
        }

        if (is_array($data)) {
            return $excelService->quickExportDecrypted($filename, $headers, $data, $decyptedColumns);
        }

        throw new InvalidArgumentException('Data must be either callabel or array');
    }
}
