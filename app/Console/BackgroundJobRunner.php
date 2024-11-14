<?php

namespace App\Console;

use Exception;

class BackgroundJobRunner
{
    public function run($className, $method, $params = []): void
    {
        $logFile = storage_path(config('app.background_log_directory'));
        $errorLogFile = storage_path(config('app.background_error_log_directory'));
        $maxRetries = config('app.max_retries');

        $retries = 0;
        $success = false;

        $approvedClasses = config('background_jobs.approved_classes');

        $className = filter_var($className, FILTER_SANITIZE_STRING);
        $method = filter_var($method, FILTER_SANITIZE_STRING);

        if (!in_array($className, $approvedClasses)) {
            $this->logJobExecution($errorLogFile, $className, $method, 'FAILED', 'Class Not Approved');
            return;
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

                $this->logJobExecution($logFile, $className, $method, 'COMPLETED');
                $success = true;

            } catch (Exception $e) {
                $retries++;
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
