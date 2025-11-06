<?php

use CodeIgniter\Database\Exceptions\DatabaseException;

if (!function_exists('logFile')) {
    function logFile(string $level, string $message, array $context = [], string $source = '')
    {
        try {
            $db = \Config\Database::connect();
            $request = \Config\Services::request();

            $data = [
                'id' => generate_uuid(),
                'level' => $level,
                'message' => $message,
                'source' => $source,
                'ip_address' => $request->getIPAddress(),
                'user_agent' => (string) $request->getUserAgent(),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (!empty($context)) {
                $data['context'] = json_encode(
                    $context,
                    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data['context'] = json_encode(['logging_error' => 'Gagal encode JSON context']);
                }
            }

            $db->table('logfile')->insert($data);
        } catch (DatabaseException $e) {
            log_message(
                'critical',
                'Database error: ' . $e->getMessage() .
                    ' | Log Asli : [' . $level . ']' . $message
            );
        } catch (\Exception $e) {
            log_message(
                'critical',
                'Error helper logfile: ' . $e->getMessage() .
                    ' | Log Asli : [' . $level . ']' . $message
            );
        }
    }
}
