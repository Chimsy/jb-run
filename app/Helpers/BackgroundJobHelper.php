<?php

namespace App\Helpers;

class BackgroundJobHelper
{
    public static function runBackgroundJob($className, $method, $params = [], $retries = 3): void
    {
        // Build the command to run the job in the background
        $command = 'php '
            . base_path('artisan') . ' background-job:run '
            . escapeshellarg($className) . ' '
            . escapeshellarg($method) . ' '
            . escapeshellarg(json_encode($params)) . ' --retries='
            . escapeshellarg($retries);

        // Log the command being executed for debugging
        file_put_contents(storage_path('logs/background_job_helper.log'),
            date('Y-m-d H:i:s') . " - Command: $command" . PHP_EOL, FILE_APPEND);

        // Check if the OS is Windows or Unix-based
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen('start /B ' . $command, 'r'));
        } else {
            exec($command . ' > /dev/null 2>&1 &');
        }
    }
}