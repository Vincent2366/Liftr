@extends('tenant.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Activity</h1>
    </div>

    <!-- Start/Stop Activity Button -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    @php
                        $activeActivity = Auth::user()->activities()
                            ->where('status', 'active')
                            ->whereNull('end_time')
                            ->latest()
                            ->first();
                    @endphp
                    
                    @if($activeActivity)
                        <form action="{{ route('tenant.user.activity.stop') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-circle btn-xl">
                                <i class="fas fa-stop fa-2x"></i>
                            </button>
                            <h4 class="mt-3">Stop Activity</h4>
                            <p class="text-muted">Click to end your current workout session</p>
                            <p class="text-info">
                                Started: {{ $activeActivity->start_time->format('h:i A') }}
                                ({{ $activeActivity->start_time->diffForHumans() }})
                            </p>
                        </form>
                    @else
                        <form action="{{ route('tenant.user.activity.start') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-circle btn-xl">
                                <i class="fas fa-play fa-2x"></i>
                            </button>
                            <h4 class="mt-3">Start Activity</h4>
                            <p class="text-muted">Click to begin your workout session</p>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="activitiesTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->start_time->format('Y-m-d') }}</td>
                                        <td>{{ $activity->start_time->format('h:i A') }}</td>
                                        <td>{{ $activity->end_time ? $activity->end_time->format('h:i A') : 'In Progress' }}</td>
                                        <td>
                                            @if($activity->end_time)
                                                {{ $activity->start_time->diffForHumans($activity->end_time, true) }}
                                            @else
                                                {{ $activity->start_time->diffForHumans(now(), true) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($activity->status == 'completed')
                                                <span class="badge badge-primary">Completed</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($activity->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No recent activities found. Start your first workout!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-circle.btn-xl {
    width: 150px;
    height: 150px;
    padding: 45px 45px;
    border-radius: 75px;
    font-size: 24px;
    line-height: 1.33;
    transition: all 0.3s ease;
}

.btn-circle.btn-xl:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#activitiesTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 10,
            "responsive": true,
            "language": {
                "emptyTable": "No activities found",
                "zeroRecords": "No matching activities found",
                "search": "Search activities:"
            },
            "columnDefs": [
                { 
                    "type": "date", 
                    "targets": 0 // Ensure column 0 (Date) is treated as date for proper sorting
                }
            ]
        });
    });
</script>
@endpush

