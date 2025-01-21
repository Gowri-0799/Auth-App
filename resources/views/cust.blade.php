@extends("layouts.default")
@section('title', "Partners")
@section('content')
<!-- <div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 240px); position: relative;"> -->

<div id="content" class="container-fluid mt-3" style="box-sizing: border-box; background-color: #f8f9fc; margin-left:240px; width:80%;" >


    <div class="container mt-5">
       <!-- Invite Partner Button -->
       <a href="{{ route('cust.display') }}" class="btn btn-primary position-absolute top-0 end-0 m-3">
            Invite Partner
        </a>
        
        <!-- Sync Button (hidden on mobile) -->
        <a href="{{ route('customers') }}" class="btn btn-primary position-absolute top-0 end-0 m-3" style="display: none;">
            Sync with Zoho
        </a>
        
        <h2 class="text-center mb-4">Partners</h2>
       
        <form method="GET" action="{{ route('customer.filter') }}" class="row mb-4 align-items-end">
           
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <label for="start_date" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <label for="end_date" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <div class="col-12 col-sm-6 col-md-2 mb-3">
                <label for="rows_to_show" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                <select name="rows_to_show" id="rows_to_show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                    <option value="10" {{ request('rows_to_show') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('rows_to_show') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('rows_to_show') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <label for="search" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
            </div>

            <!-- Submit Button -->
            <div class="col-12 col-md-1 mb-3">
                <button type="submit" class="btn button-clearlink text-primary fw-bold w-100">Submit</button>
            </div>
        </form>

        <!-- Reset Link -->
        <a href="{{ route('customerdb') }}" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a>

        <!-- Partners Table -->
        @if ($customers->isEmpty())
            <div class="d-flex justify-content-center align-items-center mt-5">
                <h3> No partners available. </h3>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover text-center table-bordered" style="background-color: #fff;">
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
                                    @if ($customer->status === 'invited')
                                        <span class="badge" style="background-color: #FFEEBA; color: #856404; padding: 5px 10px;">
                                            Invited
                                        </span>
                                    @elseif ($customer->status === 'active')
                                        <span class="badge" style="background-color: #D4EDDA; color: #155724; padding: 5px 10px;">
                                            Active
                                        </span>
                                    @else
                                        <span></span>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Update the min attribute of the End Date input when Start Date changes
        startDateInput.addEventListener('change', function () {
            const startDate = this.value; // Get selected start date
            if (startDate) {
                endDateInput.min = startDate; // Set the min attribute
            }
        });

        // Ensure the End Date is valid if already selected
        const currentStartDate = startDateInput.value;
        if (currentStartDate) {
            endDateInput.min = currentStartDate;
        }
    });
</script>
@endsection
