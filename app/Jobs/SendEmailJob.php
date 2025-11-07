<?php

namespace App\Jobs;

use CodeIgniter\Email\Email;
use App\Models\Queue\EmailQueueModel;

class SendEmailJob
{
    public function execute(string $jobId)
    {
        $emailQueue = new EmailQueueModel();
        $job = $emailQueue->where('id', $jobId)->first();

        if (!$job) {
            logFile(
                'error',
                'No pending job available',
                [
                    'message' => "No pending job available"
                ],
                'SendEmailJob::execute'
            );
            return false;
        }

        $email = \Config\Services::email();

        $email->initialize([
            'mailType' => 'html',
            'charset'  => 'utf-8',
            'protocol' => 'smtp'
        ]);

        $email->setFrom('no-reply@schlemmer.co.id', 'Schlemmer APQP Application');
        $email->setTo($job->to_email);
        $email->setSubject($job->subject);
        $email->setMessage($job->body);

        if ($email->send()) {
            $emailQueue->updateJobStatus($jobId, 'sent');
            return true;
        } else {
            $emailQueue->updateJobStatus($jobId, 'failed');
            $emailQueue->update($jobId, ['reason' => $email->printDebugger()]);
            log_message('error', 'Gagal mengirim email: ' . $email->printDebugger());
        }
    }
}
