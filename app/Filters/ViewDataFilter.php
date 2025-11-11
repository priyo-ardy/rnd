<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class ViewDataFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Service('session');

        // Pastikan session tersedia
        if (!$session->has('user_name')) {
            $session->set('user_name', 'Guest');
            // return redirect()->to(base_url());
        }

        // Perbaiki typo 'user_iamge' menjadi 'user_image'
        $data = [
            'app_ver'       => "1.0.0.dev",
            'app_name'      => "SPK Application",
            'NIK'           => $session->get('user_name') ?? '0000',
            'full_name'     => $session->get('full_name') ?? 'Unknown',
            'user_image'    => $session->get('user_image') ?? 'default.jpg', // Typo diperbaiki
            'tanggal'       => date("Y-m-d H:i:s")
        ];

        // Cara 1: Simpan di request property
        $request->viewData = $data;

        // Cara 2 (Lebih Direkomendasikan): Langsung set ke View Renderer
        $view = service('renderer');
        foreach ($data as $key => $value) {
            $view->setVar($key, $value);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
