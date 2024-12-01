<?php

namespace App\TkbgJobs;

use Illuminate\Support\Facades\Log;

class TestJob
{
    protected $param1;
    protected $param2;

    /**
     * Create a new job instance.
     *
     * @param string $param1
     * @param string $param2
     */
    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("TestJob executed successfully", [
            'param1' => $this->param1,
            'param2' => $this->param2,
        ]);
    }
}
