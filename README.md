# TK BG Custom Background Job Runner

![Alt text](resources/sample_images/2.png?raw=true "Demo2")

## Features
- **Background execution** of PHP classes and methods.
- **Configurable retry mechanism** for failed jobs.
- **Logging** of success, failure, and errors.
- **Web-based dashboard** to view job statuses and logs.
- **Error messages** displayed for failed jobs.
- **Retry attempts** available for failed jobs.

## Installation

1. Clone the repository:
   ```bash
   git clone <repository_url>
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure approved classes in `config/background_jobs.php`. 
   Add the classes that you want to run as background jobs into this configuration file.

4. Run the migrations and seed the database to set up the required tables and pre-populate the dashboard:
   ```bash
   php artisan migrate:refresh --seed
   ```

## Usage

### Running a Background Job
To run a background job, use the helper function:
```php
runBackgroundJob(\App\TkbgJobs\TestJob::class, 'run', ['param1', 'param2'], 3);
```

- `TestJob::class` is the PHP class you want to execute.
- `'run'` is the method inside that class you wish to call.
- `['param1', 'param2']` are the parameters you want to pass to the method.
- `3` is the retry attempt limit.

### Running the Processor Manually

To process background jobs manually, run the following command:
```bash
php artisan tkbg:process-jobs
```

### Running the Processor Automatically (Scheduler)

To automatically process background jobs using the Laravel scheduler, add the following line to your `app/Console/Kernel.php` under the `schedule` method:
```php
$schedule->command('tkbg:process-jobs')->everyTwoSeconds();
```

Then, run the scheduler:
```bash
php artisan schedule:work
```

This will process the jobs at regular intervals as defined in the scheduler.

## Example Commands

### Successful Command

Run a background job from the command line (or shell) using the following format:
```bash
php artisan run:background-job App\TkbgJobs\TestJob handle "Hello,World"
```
- `App\TkbgJobs\TestJob` is the PHP class you want to execute.
- `handle` is the method within that class.
- `"Hello,World"` is the COMMA-SEPERATED list of parameters passed to the method.

### Failed Command

If you attempt to run a non-existent job or method, you’ll see a failure message:
```bash
php artisan run:background-job App\TkbgJobs\UnknownJob handle "Hello,World"
```

This will trigger an error as `App\TkbgJobs\UnknownJob` doesn't exist.

## Dashboard

You can monitor the job statuses and logs via the web-based dashboard. It shows all the job executions, their status, retries, and error messages.


![Alt text](resources/sample_images/1.png?raw=true "Demo")
