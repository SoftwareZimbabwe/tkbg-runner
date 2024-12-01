<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProcessBackgroundJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tkbg:process-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending background jobs.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $job = DB::table('background_jobs')
            ->where('status', 'pending')
            ->where('attempts', '<', 5)
            ->where('scheduled_at', '<=', now())
            ->orderBy('priority', 'asc')
            ->orderBy('scheduled_at', 'asc')
            ->first();

        if (!$job) {
            $this->info('No pending jobs found.');
            return;
        }

        DB::table('background_jobs')->where('id', $job->id)->update(['status' => 'running']);

        try {
            $class = $job->class;
            $method = $job->method;
            $params = json_decode($job->params, true);

            $reflection = new \ReflectionClass($class);
            $object = $reflection->newInstanceArgs($params);

            if (!method_exists($object, $method)) {
                throw new \Exception("Method {$method} does not exist in class {$class}.");
            }

            $object->$method();

            DB::table('background_jobs')->where('id', $job->id)->update(['status' => 'completed']);
            $this->info("Job {$job->id} completed successfully.");
        } catch (\Throwable $e) {
            Log::error("Failed to execute job {$job->id}: {$e->getMessage()}");
            DB::table('background_jobs')->where('id', $job->id)->update([
                'status' => 'failed',
                'attempts' => DB::raw('attempts + 1'),
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
