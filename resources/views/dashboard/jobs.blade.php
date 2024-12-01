@extends('layouts.app')

@section('content')
<div class="container my-5">

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <div class="chart-container" style="position: relative; height:300px; width:100%;">
                <canvas id="jobStatusChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">

            <form class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-select bg-dark text-white border-secondary">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="running" {{ request('status') === 'running' ? 'selected' : '' }}>Running</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive bg-dark p-3 rounded">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $job->class }}</td>
                        <td>{{ $job->method }}</td>
                        <td>
                            <span class="badge
                                {{ $job->status === 'completed' ? 'bg-success' :
                                   ($job->status === 'failed' ? 'bg-danger' :
                                   ($job->status === 'running' ? 'bg-warning' : 'bg-secondary')) }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>{{ $job->attempts }}</td>
                        <td>{{ $job->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($job->status === 'running')
                                <form action="{{ route('dashboard.jobs.cancel', $job->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            @elseif($job->status === 'failed')
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#errorMessageModal{{ $job->id }}">View Error</button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>No Action</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No jobs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $jobs->links('pagination::bootstrap-5') }}
    </div>

    @foreach($jobs as $job)
        <div class="modal fade" id="errorMessageModal{{ $job->id }}" tabindex="-1" aria-labelledby="errorMessageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorMessageModalLabel">Error Message for Job #{{ $job->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($job->error_message)
                            <pre>{{ $job->error_message }}</pre>
                        @else
                            <p>No error message available.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    const ctx = document.getElementById('jobStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Running', 'Completed', 'Failed'],
            datasets: [{
                data: [
                    {{ $jobs->where('status', 'pending')->count() }},
                    {{ $jobs->where('status', 'running')->count() }},
                    {{ $jobs->where('status', 'completed')->count() }},
                    {{ $jobs->where('status', 'failed')->count() }}
                ],
                backgroundColor: ['#6c757d', '#ffc107', '#28a745', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#fff'
                    }
                }
            }
        }
    });
</script>
@endsection
