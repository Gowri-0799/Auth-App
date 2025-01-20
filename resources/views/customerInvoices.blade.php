@extends("layouts.admin")
@section('title', "Invoices")

@section('content')

<div id="content" class="container-fluid mt-3" style="box-sizing: border-box; margin-left:250px; width:100%;" >
    <div class="row">
    <!-- Main Content -->
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header">
                    <h2 class="mb-4 text-center text-lg-start" style="font-size: 28px;">Invoices</h2>
                </div>
                <div class="card-body p-3">
                    <!-- Table Filters Section -->
                    <form class="row g-3 mb-4" method="GET" action="{{ route('invoices.filter') }}">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="startDate" class="form-label fw-bold">Start Date</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="endDate" class="form-label fw-bold">End Date</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="showEntries" class="form-label fw-bold">Show</label>
                            <select id="showEntries" name="show" class="form-select">
                                <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="search" class="form-label fw-bold">Search</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-1 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                        </div>
                    </form>

                    <!-- Reset link -->
                    <a href="{{ route('customer.invoices') }}" class="text-decoration-none mb-3 d-inline-block text-primary">Reset</a>

                    <!-- Check for invoices -->
                    @if($invoices->count() == 0)
                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <h3>No invoices found.</h3>
                        </div>
                    @else
                        <!-- Responsive Table -->
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice Date</th>
                                        <th>Invoice Number</th>
                                        <th>Payment Mode</th>
                                        <th>Plan Name</th>
                                        <th>Plan Price (USD)</th>
                                        <th>Invoice Price (USD)</th>
                                        <th>Credits Applied (USD)</th>
                                        <th>Payment Made (USD)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                                        <td><a href="{{ $invoice->invoice_link }}" target="_blank" class="text-decoration-none text-primary">{{ $invoice->invoice_number }}</a></td>
                                        @php
                                        $paymentDetails = json_decode($invoice->payment_details, true);
                                        @endphp
                                        <td>{{ $paymentDetails[0]['payment_mode'] ?? 'N/A' }}</td>
                                        @php
                                        $invoiceItems = json_decode($invoice->invoice_items, true);
                                        @endphp
                                        <td>{{ $invoiceItems[0]['code'] ?? 'N/A' }}</td>
                                        <td>{{ number_format($invoiceItems[0]['price'] ?? 0, 2) }}</td>
                                        <td>{{ number_format($invoiceItems[0]['price'] ?? 0, 2) }}</td>
                                        <td>{{ number_format($invoice->credits_applied, 2) }}</td>
                                        <td>{{ number_format($invoice->payment_made, 2) }}</td>
                                        <td>
                                            @if(strtolower($invoice->status) == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger">Pending</span>
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

        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
    });
</script>
@endsection
