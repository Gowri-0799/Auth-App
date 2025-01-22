@extends("layouts.default")
@section('title', "Terms Log")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc;">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="font-size: 30px;">Terms Log</h2>
            </div>
            <div class="card-body p-3">
                <!-- Filter Form -->
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('term.adfilter') }}" style="margin-top: 20px;">
                <div class="col-md-2">
                        <label for="startDate" class="fw-bold" style="font-size: 14px;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="fw-bold" style="font-size: 14px;">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="fw-bold" style="font-size: 14px;">Show Entries</label>
                        <select id="showEntries" name="show" class="form-select">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="fw-bold" style="font-size: 14px;">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search.." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>

                <a href="{{ route('terms.log') }}" class="btn btn-link fw-bold p-0 mb-3">Reset</a>

                <!-- Display Table or No Data Message -->
                @if($terms->isEmpty())
                <div class="d-flex justify-content-center align-items-center mt-5">
                <h3>No terms log data found</h3>
                  
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center" style="background-color:#fff;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Subscription Number</th>
                                    <th>Logged at</th>
                                    <th>Company Name</th>
                                    <th>Partner User Name</th>
                                    <th>IP Address</th>
                                    <th>Browser Agent</th>
                                    <th>Consent</th>
                                    <th>Plan Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($terms as $index => $term)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $term->subscription_number }}</td>
                                        <td></td>
                                        <td>{{ $term->company_name }}</td>
                                        <td>{{ $term->customer_name }}</td>
                                        <td>{{ $term->ip_address }}</td>
                                        <td>{{ $term->browser_agent }}</td>
                                        <td>{{ $term->consent == 1 ? 'Yes' : 'No' }}</td>
                                        <td>{{ $term->plan_name }}</td>
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

<style>
    /* Custom modal styling */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    .modal-like-form {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background-color: #fff;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
