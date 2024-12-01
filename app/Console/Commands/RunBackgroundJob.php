<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunBackgroundJob extends Command
{
    protected $signature = 'run:background-job {class} {method} {params?} {--retryAttempts=3}';
    protected $description = 'Run a background job';

    public function handle()
    {
        try {

            $class = $this->argument('class');
            $method = $this->argument('method');
            $paramsString = $this->argument('params') ?? '';
            $retryAttempts = (int) $this->option('retryAttempts');

            $params = array_map('trim', explode(',', $paramsString));

            $this->info("Running job: {$class}@{$method}");
            $this->info("Parameters: " . implode(', ', $params));
            $this->info("Retry Attempts: {$retryAttempts}");


            runBackgroundJob($class, $method, $params, $retryAttempts);
        } catch (\Exception $e) {
            Log::error("Job scheduling failed: {$e->getMessage()}");
            $this->error("Failed to execute job: {$e->getMessage()}");
        }
    }
}
