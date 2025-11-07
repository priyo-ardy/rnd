<?php

namespace App\Controllers\Test\Email;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Email extends BaseController
{
    public function sendTest()
    {
        $email = \Config\Services::email();

        $email->setTo('priyo.ardy@yahoo.com'); // Ganti dengan email tester
        $email->setSubject('Test Email dari CodeIgniter 4');
        $email->setMessage('
            <h1>Test Email</h1>
            <p>Ini adalah test email dari aplikasi CodeIgniter 4.</p>
            <p>Waktu: ' . date('Y-m-d H:i:s') . '</p>
        ');

        if ($email->send()) {
            echo 'Email berhasil dikirim!';
            echo '<br>Periksa folder spam jika tidak ditemukan di inbox.';
        } else {
            echo 'Gagal mengirim email';
            echo '<pre>';
            print_r($email->printDebugger(['headers']));
            echo '</pre>';
        }
    }

    public function testConnection()
    {
        $email = \Config\Services::email();

        // Test koneksi SMTP
        try {
            $email->initialize([
                'SMTPHost' => 'smtp.gmail.com',
                'SMTPUser' => 'schlemmerid.dev@gmail.com',
                'SMTPPass' => 'qbzwvghjeqycxkxy',
                'SMTPPort' => 587,
                'SMTPCrypto' => 'tls',
                'protocol' => 'smtp'
            ]);

            echo "Koneksi SMTP berhasil diinisialisasi";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
