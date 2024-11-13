<?php

namespace App\Console;

use Exception;

class BackgroundJobRunner
{
    protected int $maxRetries;

    // TODO: Make Number of MaxRetries Configurable
    public function __construct($maxRetries = 3)
    {
        $this->maxRetries = $maxRetries;
    }

    public function run($className, $method, $params = []): void
    {
        $logFile = storage_path('logs/background_jobs.log');
        $errorLogFile = storage_path('logs/background_jobs_errors.log');
        $retries = 0;
        $success = false;

        $approvedClasses = config('background_jobs.approved_classes');

        $className = filter_var($className, FILTER_SANITIZE_STRING);
        $method = filter_var($method, FILTER_SANITIZE_STRING);

        if (!in_array($className, $approvedClasses)) {
            $this->logJobExecution($errorLogFile, $className, $method, 'FAILED', 'Class Not Approved');
            return;
        }

        while ($retries < $this->maxRetries && !$success) {
            try {
                if (!class_exists($className)) {
                    throw new Exception("Class $className Not Found.");
                }
                $classInstance = new $className();

                if (!method_exists($classInstance, $method)) {
                    throw new Exception("Method $method Not Found in Class $className.");
                }

                // Log job is running
                $this->logJobExecution($logFile, $className, $method, 'RUNNING');

                call_user_func_array([$classInstance, $method], $params);

                // Log job success
                $this->logJobExecution($logFile, $className, $method, 'COMPLETED');
                $success = true;

            } catch (Exception $e) {
                $retries++;
                $this->logJobExecution($errorLogFile, $className, $method, 'FAILED', $e->getMessage());

                if ($retries >= $this->maxRetries) {
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
