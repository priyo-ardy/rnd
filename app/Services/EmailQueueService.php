<?php

namespace App\Services;

use App\Models\Queue\EmailQueueModel;
use Config\Services;

class EmailQueueService
{
    protected $emailModel;
    protected $email;

    public function __construct()
    {
        $this->emailModel = new EmailQueueModel();
        $this->email = Services::email();
    }

    public function queueEmail(string $toEmail, string $subject, string $body)
    {
        $emailData = [
            'id' => generate_uuid(),
            'to_email' => $toEmail,
            'subject' => $subject,
            'body' => $body,
        ];

        return $this->queueModel->insert($emailData);
    }
}
