<?php

namespace App\Console;

use Exception;

class BackgroundJobRunner
{
    protected $maxRetries;

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

        while ($retries < $this->maxRetries && !$success) {
            try {
                if (!class_exists($className)) {
                    throw new Exception("Class $className not found.");
                }
                $classInstance = new $className();

                if (!method_exists($classInstance, $method)) {
                    throw new Exception("Method $method not found in class $className.");
                }

                $this->logJobExecution($logFile, $className, $method, 'running');

                call_user_func_array([$classInstance, $method], $params);

                $this->logJobExecution($logFile, $className, $method, 'completed');
                $success = true;

            } catch (Exception $e) {
                $retries++;

                $this->logJobExecution($errorLogFile, $className, $method, 'failed', $e->getMessage());

                if ($retries >= $this->maxRetries) {
                    $this->logJobExecution($logFile, $className, $method, 'failed');
                }
            }
        }
    }

    private function logJobExecution($logFile, $className, $method, $status, $errorMessage = ''): void
    {
        $logMessage = date('Y-m-d H:i:s') . " - Class: $className, Method: $method, Status: $status";

        if ($status == 'failure') {
            $logMessage .= ", Error: $errorMessage";
        }

        file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);
    }
}
