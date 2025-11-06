<?php

use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

if (!function_exists('generate_uuid')) {
    function generate_uuid()
    {
        $data = random_bytes(16);

        // Mengatur versi dan varian sesuai dengan spesifikasi UUID
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versi 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // varian 10xx

        // Mengonversi byte menjadi string UUID dengan format yang benar
        return sprintf(
            '%s-%s-%s-%s-%s',
            bin2hex(substr($data, 0, 4)),
            bin2hex(substr($data, 4, 2)),
            bin2hex(substr($data, 6, 2)),
            bin2hex(substr($data, 8, 2)),
            bin2hex(substr($data, 10, 6))
        );
    }
}

if (!function_exists('calculate_duration_days')) {
    function calculate_duration_days($start_date, $end_date)
    {
        // Convert the dates to DateTime objects
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        // Calculate the difference
        $interval = $start->diff($end);

        // Return the number of days
        return $interval->days;
    }
}

if (!function_exists('calculate_time_duration')) {
    function calculate_time_duration($start_time, $end_time)
    {
        // Convert the times to DateTime objects
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);

        // Calculate the difference
        $interval = $start->diff($end);

        // Format the duration
        return $interval->format('%H:%I:%S');
    }
}

if (!function_exists('convert_time_to_decimal')) {
    function convert_time_to_decimal($time)
    {
        // Convert time to decimal format
        $time_parts = explode(':', $time);
        $hours = (int)$time_parts[0]; //Pecah menjadi jam
        $minutes = (int)$time_parts[1]; //Pecah menjadi menit
        $seconds = (int)$time_parts[2]; //Pecah menjadi detik

        // Hasilkan data menjadi format desimal
        return $hours + ($minutes / 60) + ($seconds / 3600);
    }
}

if (!function_exists('convert_decimal_to_time')) {
    function convert_decimal_to_time($decimal)
    {
        // Convert decimal to time format
        $hours = floor($decimal); //Pecah menjadi jam
        $minutes = floor(($decimal - $hours) * 60); //Pecah menjadi menit
        $seconds = floor((($decimal - $hours) * 60 - $minutes) * 60); //Pecah menjadi detik

        // Hasilkan data dalam bentuk HH:mm:ss
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}

if (!function_exists(('get_user_hostname'))) {
    function get_user_hostname()
    {
        // Get the hostname of the user
        return gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }
}

if (!function_exists('get_user_agent')) {
    function get_user_agent()
    {
        // Get the user agent of the user
        return $_SERVER['HTTP_USER_AGENT'];
    }
}

if (!function_exists('log_action')) {
    function log_action($module, $action, $status, $pages, $message, $old_data = null, $new_data = null)
    {
        $enkripsi = Services::encrypter();
        // Create a new log entry
        $logData = [
            'id'                => generate_uuid(),
            'module'            => $module,
            'action'            => $action,
            'action_status'     => $status,
            'pages'             => $pages,
            'message'           => $message,
            'user_name'         => session()->get('full_name'),
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'hostname'          => get_user_hostname(),
            'user_agent'        => get_user_agent(),
            'old_data'          => $old_data,
            'new_data'          => $new_data,
            'created_at'        => date('Y-m-d H:i:s'),
        ];

        $db      = \Config\Database::connect(); //Membuat koneksi ke database yang telah disetup pada file .env
        $builder = $db->table('user_log'); //Memilih table

        $builder->insert($logData); //Insert ke table
    }
}

if (!function_exists('generate_random_code')) {
    function generate_random_code($length = 8)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $random_code = '';

        for ($i = 0; $i < $length; $i++) {
            $random_code .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $random_code;
    }
}

if (!function_exists('pesan_error')) {
    function pesan_error($response, $exception, $message = null, $trace = null)
    {
        log_message('error', 'Exception: ' . $message . ' in ' . $exception->getFile() . ':' . $exception->getLine());
        return $response
            ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'Unexpected Error')
            ->setJSON([
                'message' => $message,
                'trace' => $trace,
                'csrf_token' => csrf_hash()
            ]);
    }
}

if (!function_exists('system_info')) {
    function system_info()
    {
        $db = \Config\Database::connect();
        $dbDriver = $db->getPlatform();

        // Untuk mendapatkan versi database
        try {
            if ($dbDriver === 'MySQLi') {
                $version = $db->query('SELECT VERSION() as version')->getRow()->version;
            } elseif ($dbDriver === 'Postgre') {
                $version = $db->query('SELECT version()')->getRow()->version;
            } else {
                $version = 'Unknown';
            }
        } catch (\Exception $e) {
            $version = 'Error: ' . $e->getMessage();
        }

        return [
            'PHP Version' => phpversion(),
            'CodeIgniter Version' => \CodeIgniter\CodeIgniter::CI_VERSION,
            'Database Driver' => $dbDriver,
            'Database Version' => $version,
            'OS' => php_uname('s') . ' ' . php_uname('r'),
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'Server Name' => $_SERVER['SERVER_NAME'] ?? 'N/A',
            'Environment' => ENVIRONMENT,
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'N/A',
            'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
            'php_memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'loaded_extensions' => implode(', ', get_loaded_extensions()),
        ];
    }
}

if (!function_exists('current_url')) {
    function current_url()
    {
        // Mendapatkan objek request
        $request = service('request');
        // Mendapatkan current URL
        $currentUrl = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $request->getUri()->getPath();
        // Jika Anda juga ingin menambahkan query string
        $currentUrl .= $request->getUri()->getQuery() ? '?' . $request->getUri()->getQuery() : '';
        // Menampilkan current URL
        return $currentUrl;
    }
}

if (!function_exists('pesan')) {
    function pesan(string $error_code, string $message, $data =  null)
    {
        $response = service('response');
        return $response
            ->setStatusCode($error_code)
            ->setJSON([
                'status' => $error_code,
                'message' => $message,
                'data' => $data
            ]);
    }
}

if (!function_exists('enkripsi')) {
    function enkripsi($value)
    {
        $encrypter = service('encrypter');

        return bin2hex(base64_encode($encrypter->encrypt($value)));
    }
}

if (!function_exists('dekripsi')) {
    function dekripsi($value)
    {
        $decrypter = service('encrypter');
        // $level_1 = hex2bin($value);
        // $level_2 = base64_decode($level_1);
        // $level_3 = $decrypter->decrypt($level_2);


        return $decrypter->decrypt(base64_decode(hex2bin($value)));
        // return base64_decode(hex2bin($value));
        // return $level_2;
    }
}

if (!function_exists('phone_hash')) {
    function phone_hash(string $phone_number)
    {
        $secret_key = getenv('phone_salt');
        return hash('sha256', $secret_key . $phone_number);
    }
}

if (!function_exists('email_hash')) {
    function email_hash(string $email_address)
    {
        $secret_key = getenv('phone_salt');
        return hash('sha256', $secret_key . $email_address);
    }
}

function sensor_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return '***@***';
    }

    list($username, $domain) = explode('@', $email);
    $domainParts = explode('.', $domain);
    $tld = array_pop($domainParts);
    $domainName = implode('.', $domainParts);

    // Mask username (tampilkan 2 karakter pertama, 1 karakter terakhir)
    $usernameLength = strlen($username);
    if ($usernameLength <= 3) {
        $maskedUsername = str_repeat('*', $usernameLength);
    } else {
        $firstChars = substr($username, 0, 2);
        $lastChar = substr($username, -1);
        $maskedPart = str_repeat('*', $usernameLength - 3);
        $maskedUsername = $firstChars . $maskedPart . $lastChar;
    }

    // Mask domain name (tampilkan 2 karakter pertama)
    $domainNameLength = strlen($domainName);
    if ($domainNameLength <= 2) {
        $maskedDomainName = str_repeat('*', $domainNameLength);
    } else {
        $firstChars = substr($domainName, 0, 2);
        $maskedPart = str_repeat('*', $domainNameLength - 2);
        $maskedDomainName = $firstChars . $maskedPart;
    }

    return $maskedUsername . '@' . $maskedDomainName . '.' . $tld;
}
