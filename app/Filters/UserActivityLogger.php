<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserActivityLogger implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $logData = [
            'method' => $request->getMethod(),
            'uri' => $request->getUri()->getPath(),
            'ip' => $request->getIPAddress(),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        log_message('info', 'Request: ' . json_encode($logData));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
