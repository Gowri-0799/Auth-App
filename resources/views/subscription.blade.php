@extends("layouts.default")
@section('title', "All Subscriptions")

@section('content')
<!-- <div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);"> -->
<div id="content" class="container-fluid mt-3" style="box-sizing: border-box; margin-left:240px; width:80%;" >


    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <h2 class="mb-4 text-center text-md-start" style="font-size: 24px;">Subscriptions</h2>
            </div>
            
            <div class="card-body p-3">
                <!-- Table Filters Section -->
                <form class="row g-3 mb-4 align-items-end" method="GET" action="{{ route('subscriptions.filter') }}">
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="startDate" class="form-label fw-bold">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="endDate" class="form-label fw-bold">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <label for="showEntries" class="form-label fw-bold">Show</label>
                        <select id="showEntries" name="show" class="form-select">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="search" class="form-label fw-bold">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                    </div>
                 
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>
                
                <!-- Reset link -->
                <a href="{{ route('subdata') }}" class="text-decoration-none mb-3 d-inline-block text-primary">Reset</a>

                <!-- Check for subscriptions -->
                @if($subscriptions->count() == 0)
                    <div class="d-flex justify-content-center align-items-center mt-5">
                        <h3>No subscriptions found.</h3>
                    </div>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>S.No</th>
                                    <th>Subscription Number</th>
                                    <th>Company Name</th>
                                    <th>Plan Name</th>
                                    <th>Plan Amount</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $index => $subscription)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subscription->subscription_number }}</td>
                                        <td>{{ $subscription->company_name }}</td>
                                        <td>{{ $subscription->plan_name }}</td>
                                        <td>${{ number_format($subscription->plan_price, 2) }}</td>
                                        <td>{{ $subscription->start_date }}</td>
                                        <td>{{ $subscription->next_billing_at }}</td>
                                        <td>
                                            @if(strtolower($subscription->status) == 'live')
                                                <span class="badge bg-success">Live</span>
                                            @elseif(strtolower($subscription->status) == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>  
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        startDateInput.addEventListener('change', function () {
            const startDate = this.value;
            if (startDate) {
                endDateInput.min = startDate; 
            }
        });

        const currentStartDate = startDateInput.value;
        if (currentStartDate) {
            endDateInput.min = currentStartDate;
        }
    });
</script>
@endsection
