@extends("layouts.admin")
@section('title', "Invoices")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <!-- Title with margin-bottom -->
                <h2 class="mb-5" style="font-size: 30px;">Invoices</h2> <!-- Added margin-bottom to create space below the title -->
            </div>
            
            <div class="card-body p-3">
                <!-- Table Filters Section -->
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('invoices.filter') }}"> <!-- Increased margin-bottom -->
                    <div class="col-md-2">
                        <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">Show</label>
                        <select id="showEntries" name="show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                       <label for="search" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">Search</label>
                       <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>

                <!-- Reset link with top margin for spacing -->
                <a href="{{ route('customer.invoices') }}" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a> <!-- Added margin-bottom -->

                <!-- Check for invoices -->
                @if($invoices->count() == 0)
                <div class="d-flex justify-content-center align-items-center mt-5">
                <h3>No invoices found.</h3>
                  
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color: #fff; width: 100%; max-width: 100%;">
                            <thead class="table-light" > <!-- Updated header background color -->
                                <tr>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">#</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Invoice Date</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Invoice Number</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Payment Mode</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Plan Name</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Plan Price (USD)</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Invoice Price (USD)</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Credits Applied (USD)</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Payment Made (USD)</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #e9ecef;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $index => $invoice)
                                <tr>
                                    <td>{{ (int)$index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                                    <td><a href="{{ $invoice->invoice_link }}" target="_blank" class="text-decoration-none text-primary">{{ $invoice->invoice_number }}</a></td>
                                    @php
                                    $paymentDetails = json_decode($invoice->payment_details, true);
                                    @endphp 
                                    <td>{{ $paymentDetails[0]['payment_mode'] ?? 'N/A' }}</td>
                                    
                                    @php
                                    $invoiceItems = json_decode($invoice->invoice_items, true);
                                    @endphp 
                                    <td>{{ $invoiceItems[0]['code'] ?? 'N/A' }}</td> <!-- Display plan name from JSON -->
                                    <td>{{ number_format($invoiceItems[0]['price'] ?? 0, 2) }}</td>
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
@endsection
