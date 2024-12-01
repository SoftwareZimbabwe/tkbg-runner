<?php

namespace App\Http\Controllers;

use App\Models\BackgroundJob;
use Illuminate\Http\Request;

class BackgroundJobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = BackgroundJob::query();

        if ($request->filled('status')) {
            $jobs->where('status', $request->input('status'));
        }

        $jobs->orderBy('created_at', 'desc');

        return view('dashboard.jobs', [
            'jobs' => $jobs->paginate(10),
        ]);
    }

    public function cancel($id)
    {
        $job = BackgroundJob::find($id);

        if ($job && $job->status === 'running') {
            $job->status = 'cancelled';
            $job->save();
        }

        return redirect()->route('dashboard.jobs');
    }
}
