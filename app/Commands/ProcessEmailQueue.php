<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Models\Queue\EmailQueueModel;
use App\Jobs\SendEmailJob;
use CodeIgniter\CLI\CLI;
use Config\Email;

class ProcessEmailQueue extends BaseCommand
{
    protected $group = 'queue';
    protected $name = 'queue:email';
    protected $description = "Proses queue email yang pending";

    public function run(array $params)
    {
        $emailModel = new EmailQueueModel();
        $pendingJobs = $emailModel->getPendingJobs();

        foreach ($pendingJobs as $job) {
            $sendEmailJob = new SendEmailJob();
            $sendEmailJob->execute($job->id);
            CLI::write("Memproses job ID: {$job->id}\n");
        }

        CLI::write("Proses email queue selesai dengan total " . count($pendingJobs) . " data.\n");
    }
}
