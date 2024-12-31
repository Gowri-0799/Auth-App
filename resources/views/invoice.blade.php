@extends("layouts.default")
@section('title', "All Invoices")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <h2 class="mb-5" style="font-size: 30px;">All Invoices</h2>
            </div>
            
            <div class="card-body p-3"> 
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('invoices.adfilter') }}">
                    <div class="col-md-2">
                        <label for="startDate" class="form-label fw-bold">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="form-label fw-bold">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="form-label fw-bold">Show</label>
                        <select id="showEntries" name="show" class="form-select">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                       <label for="search" class="form-label fw-bold">Search</label>
                       <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>

                <a href="{{ route('invdata') }}" class="text-decoration-none mb-3 d-inline-block text-primary">Reset</a>

                @if($invoices->count() == 0)
                <div class="d-flex justify-content-center align-items-center mt-5">
                <h3>No invoices found.</h3>
@else
    <div class="table-responsive">
        <table class="table table-hover text-center table-bordered">
            <thead class="table-light">
                <tr>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">#</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Invoice Date</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Invoice Number</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Payment Mode</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Company Name</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Plan Name</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Plan Price (USD)</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Invoice Price (USD)</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Credits Applied (USD)</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Payment Received (USD)</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $index => $invoice)
                    <tr>
                        <td>{{ (int)$index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                        <td>{{ $invoice->invoice_number }}</td>

                        <td>{{ $invoice->payment_mode ?? 'N/A' }}</td>
                        <td>{{ $invoice->company_name ?? 'N/A' }}</td> <!-- Display company name -->
                        <td>{{ $invoice->plan_name ?? 'N/A' }}</td> <!-- Display plan name -->
                        <td>{{ number_format($invoice->plan_price, 2) }}</td>
                        <td>{{ number_format($invoiceItems[0]['price'] ?? 0, 2) }}</td>
                        <td>{{ number_format($invoice->credits_applied, 2) }}</td>
                        <td>{{ number_format($invoice->payment_made, 2) }}</td>
                        <td>
                            @if(strtolower($invoice->status) == 'paid')
                                <span class="badge-success">Paid</span>
                            @else
                                <span class="badge-fail">Pending</span>
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
