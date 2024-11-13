<?php

namespace App\Console;


use Exception;

class BackgroundJobRunner
{
    public function run($className, $method, $params = []): void
    {
        //TODO: Create the logfile as an ENV variable
        $logFile = storage_path(config('app.background_log_directory'));

        try {
            if (!class_exists($className)) {
                throw new Exception("Class $className not found.");
            }
            $classInstance = new $className();

            if (!method_exists($classInstance, $method)) {
                throw new Exception("Method $method not found in class $className.");
            }
            call_user_func_array([$classInstance, $method], $params);

            $this->logJobExecution($logFile, $className, $method, 'success');
        } catch (Exception $e) {
            $this->logJobExecution($logFile, $className, $method, 'failure', $e->getMessage());
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

// Example usage
$jobRunner = new BackgroundJobRunner();
$jobRunner->run('SomeClass', 'someMethod', ['param1', 'param2']);

