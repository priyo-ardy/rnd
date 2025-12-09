<?php

namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Panggil layanan throttler bawaan CI4
        $throttler = Services::throttler();

        // 2. Tentukan batas (limit) dalam waktu (Time)
        // Kita bisa mengambilnya dari argumen di routes, atau gunakan default
        // Dormat argumen: [limit, second]
        // Default: 60 request per 60 detik (1 menit)
        $limit = $arguments[0] ?? 60;
        $seconds = $arguments[1] ?? 60;

        $session = session();
        if ($session->has('user_name')) {
            $identifier = 'user_' . $session->get('user_name');
        } else {
            $identifier = 'ip_' . $request->getIPAddress();
        }

        // 3. Buat kunci unik berdasarkan IP Address pengguna
        // Kita gunakan md5 agar stringnya rapi
        $path = $request->getUri()->getPath();
        $rawKey = $identifier . '_' . $path;
        $key = md5('rate_limit_' . $rawKey);

        // 4. Cek apakah pengguna sudah melewati batas
        // Fungsi check() mengembalikan FALSE jika kuota habis
        if ($throttler->check($key, $limit, $seconds) === false) {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_TOO_MANY_REQUESTS)
                ->setBody("Too many requests");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // throw new \Exception('Not implemented');
    }
}
