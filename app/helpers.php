<?php

if (!function_exists('runBackgroundJob')) {
    function runBackgroundJob($class, $method, $params = [], $priority = 1, $delayInSeconds = 0)
    {
        $approvedClasses = config('background_jobs.approved_classes');
        if (!in_array($class, $approvedClasses ?? [])) {
            throw new \Exception("Class {$class} is not approved for background execution.");
        }

        $scheduledAt = now()->addSeconds($delayInSeconds);

        \DB::table('background_jobs')->insert([
            'class' => $class,
            'method' => $method,
            'params' => json_encode($params),
            'priority' => $priority,
            'scheduled_at' => $scheduledAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }
}
