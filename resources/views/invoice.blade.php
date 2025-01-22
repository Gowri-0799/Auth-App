@extends("layouts.default")
@section('title', "All Invoices")
@section('content')

<!-- <div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);"> -->
<div id="content" class="container-fluid mt-3" style="box-sizing: border-box;" >

    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <h2 class="mb-4 text-center text-md-start" style="font-size: 24px;">All Invoices</h2>
            </div>

            <div class="card-body p-3">
                <!-- Filters Form -->
                <form class="row gy-3 gx-3 mb-4 align-items-end" method="GET" action="{{ route('invoices.adfilter') }}">
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="startDate" class="form-label fw-bold">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
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

                <!-- Reset Link -->
                <a href="{{ route('invdata') }}" class="text-decoration-none mb-3 d-inline-block text-primary">Reset</a>

                <!-- Invoices Table -->
                @if($invoices->count() == 0)
                <div class="d-flex justify-content-center align-items-center mt-5">
                    <h3>No invoices found.</h3>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover text-center table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Invoice Date</th>
                                <th>Invoice Number</th>
                                <th>Payment Mode</th>
                                <th>Company Name</th>
                                <th>Plan Name</th>
                                <th>Plan Price (USD)</th>
                                <th>Invoice Price (USD)</th>
                                <th>Credits Applied (USD)</th>
                                <th>Payment Received (USD)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->payment_mode ?? 'N/A' }}</td>
                                <td>{{ $invoice->company_name ?? 'N/A' }}</td>
                                <td>{{ $invoice->plan_name ?? 'N/A' }}</td>
                                <td>${{ number_format($invoice->plan_price, 2) }}</td>
                                <td>${{ number_format($invoiceItems[0]['price'] ?? 0, 2) }}</td>
                                <td>${{ number_format($invoice->credits_applied, 2) }}</td>
                                <td>${{ number_format($invoice->payment_made, 2) }}</td>
                                <td>
                                    @if(strtolower($invoice->status) == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                    @else
                                    <span class="badge bg-warning text-dark">Pending</span>
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