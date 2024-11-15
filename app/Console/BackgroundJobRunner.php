<?php

namespace App\Console;

use App\Models\BackgroundJob;
use Exception;

class BackgroundJobRunner
{
    public function run($className, $method, $params = [], $delay = 0): void
    {
        $approvedClasses = config('background_jobs.approved_classes');
        $maxRetries = config('app.max_retries');

        $logFile = storage_path(config('app.background_log_directory'));
        $errorLogFile = storage_path(config('app.background_error_log_directory'));

        $retries = 0;
        $success = false;

        $className = filter_var($className, FILTER_SANITIZE_STRING);
        $method = filter_var($method, FILTER_SANITIZE_STRING);

        if (!in_array($className, $approvedClasses)) {
            $this->logJobExecution($errorLogFile, $className, $method, 'FAILED', 'Class Not Approved');
            return;
        }

        // Create a new job record
        $job = BackgroundJob::create([
            'class_name' => $className,
            'method_name' => $method,
            'params' => json_encode($params),
            'status' => 'RUNNING',
        ]);

        // Delay the execution if delay is specified
        if ($delay > 0) {
            sleep($delay);
        }

        while ($retries < $maxRetries && !$success) {
            try {
                if (!class_exists($className)) {
                    throw new Exception("Class $className Not Found.");
                }

                $classInstance = new $className();

                if (!method_exists($classInstance, $method)) {
                    throw new Exception("Method $method Not Found in Class $className.");
                }

                $this->logJobExecution($logFile, $className, $method, 'RUNNING');

                call_user_func_array([$classInstance, $method], $params);

                $job->update(['status' => 'COMPLETED', 'is_running' => false]);

                $this->logJobExecution($logFile, $className, $method, 'COMPLETED');
                $success = true;

            } catch (Exception $e) {
                $retries++;

                $job->increment('retry_count');
                $job->update(['status' => 'FAILED', 'error_message' => $e->getMessage(), 'is_running' => false]);

                $this->logJobExecution($errorLogFile, $className, $method, 'FAILED', $e->getMessage());

                if ($retries >= $maxRetries) {
                    $this->logJobExecution($logFile, $className, $method, 'FAILED');
                }
            }
        }
    }

    private function logJobExecution($logFile, $className, $method, $status, $errorMessage = ''): void
    {
        $logMessage = date('Y-m-d H:i:s') . " - Class: $className, Method: $method, Status: $status";

        if ($status == 'FAILED') {
            $logMessage .= ", Error: $errorMessage";
        }

        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    }
}
