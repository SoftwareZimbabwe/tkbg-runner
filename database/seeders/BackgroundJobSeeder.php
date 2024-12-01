<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BackgroundJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('background_jobs')->insert([
            [
                'class' => 'App\\TkbgJobs\\TestJob',
                'method' => 'handle',
                'params' => json_encode(['param1' => 'Test1', 'param2' => 'Test2']),
                'priority' => 1,
                'scheduled_at' => $now->addMinutes(5),
                'attempts' => 0,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'class' => 'App\\TkbgJobs\\AnotherJob',
                'method' => 'handle',
                'params' => json_encode(['param1' => 'Sample1', 'param2' => 'Sample2']),
                'priority' => 2,
                'scheduled_at' => $now->addMinutes(10),
                'attempts' => 0,
                'status' => 'failed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'class' => 'App\\TkbgJobs\\TestJob',
                'method' => 'handle',
                'params' => json_encode(['param1' => 'Example1', 'param2' => 'Example2']),
                'priority' => 3,
                'scheduled_at' => $now->addMinutes(15),
                'attempts' => 0,
                'status' => 'completed',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
