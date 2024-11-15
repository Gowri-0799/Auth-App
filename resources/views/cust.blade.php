@extends("layouts.default")
@section('title', "Partners")
@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">

    <div class="container mt-5">
       
        <a href="{{ route('cust.display') }}" class="btn btn-primary position-absolute top-0 end-0 m-3">
            Invite Partner
        </a>
        <br>
        <a href="{{ route('customers') }}" class="btn btn-primary position-absolute top-0 end-0 m-3" style="display: none;">
            Sync with zoho
        </a>
        <h2 class="text-center mb-4">Partners</h2>
       
        <form method="GET" action="{{ route('customer.filter') }}" class="row mb-4 align-items-end">
           
            <div class="col-md-2">
                <label for="start_date" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <div class="col-md-2">
                <label for="end_date" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <div class="col-md-2">
                <label for="rows_to_show" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                <select name="rows_to_show" id="rows_to_show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                    <option value="10" {{ request('rows_to_show') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('rows_to_show') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('rows_to_show') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="search" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <!-- Submit Button -->
            <div class="col-md-1">
                <button type="submit" class="btn button-clearlink text-primary fw-bold">Submit</button>
            </div>
        </form>

        <!-- Reset Link -->
        <a href="{{ route('customerdb') }}" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a>

        <!-- Partners Table -->
        @if ($customers->isEmpty())
            <div class="alert  text-center">
                No partners available.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover text-center table-bordered" style="background-color: #fff; width: 100%; max-width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">S.No</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Company Name</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Status</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $key => $customer)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $customer->company_name }}</td>
                                <td>
                                    <!-- Dynamic Status -->
                                    @if ($customer->status === 'Setup In Progress')
                                        <span class="badge" style="background-color: #FFEEBA; color: #856404; padding: 5px 10px;">
                                            Setup In Progress
                                        </span>
                                    @elseif ($customer->status === 'Setup Completed')
                                        <span class="badge" style="background-color: #D4EDDA; color: #155724; padding: 5px 10px;">
                                            Setup Completed
                                        </span>
                                    @endif
                                </td>
                                <td>                                   
                                <a href="{{ route('customers.show', ['zohocust_id' => $customer->zohocust_id, 'section' => request()->query('section', 'overview')]) }}" class="btn button-clearlink text-primary fw-bold">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
