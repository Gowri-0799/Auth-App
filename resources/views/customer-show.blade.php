@extends("layouts.default")

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px;" class="p-3">
    <!-- Top Navigation Tabs -->
    <div class="d-flex justify-content-between align-items-center my-3">
        <h3>{{ $customer->name }}</h3>
        
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link " href="{{ route('customers.show', $customer->zohocust_id) }}" onclick="showSection('overview')">Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showSection('subscriptions')">Subscriptions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" onclick="showSection('invoices')">Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Credit Notes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Refunds</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Provider Data</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Clicks Data</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Select Plans</a>
        </li>
    </ul>

     <!-- Overview Section (default) -->
      <div id="overview" class="row mt-4">
        <!-- Account Details Section -->
        <div class="col-lg-6">
            <div class="card w-100 border-0 bg-clearlink rounded mb-3">
                <div class="card-body">
                    <h4 class="right-margin">Account Details</h4>
                    <p class="m-0">
                        <i class="fa fa-building right-margin text-primary" aria-hidden="true"></i>
                        <strong>{{ $customer->company_name }}</strong>
                    </p>
                    <p class="m-0">
                        <i class="fa fa-user right-margin text-primary" aria-hidden="true"></i>
                        <strong>{{ $customer->customer_name }}</strong>
                    </p>
                   
                    <h5 class="right-margin">Address Details</h5>
                    <div class="d-flex flex-row mb-3">
                        <div class="m-0">
                            <i class="fa fa-address-card right-margin text-primary" aria-hidden="true"></i>
                        </div>
                        <div>
                            {{ $customer->billing_street }}, <br>
                            {{ $customer->billing_city }}, <br>
                            {{ $customer->billing_state }}, <br>
                            {{ $customer->billing_country }}<br>
                            {{ $customer->billing_zip }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div class="col-lg-6">
            <div class="card w-100 border-0 bg-clearlink rounded mb-3">
                <div class="card-body">
                    <div class="d-flex flex-row mb-5 justify-content-between">
                        <h4 class="ms-3">Users</h4>
                        <a data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-primary btn-sm me-3">Invite User</a>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        No secondary users found
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Subscriptions Section -->
   <div id="subscriptions" class="mt-4" style="display: none;">
        @if($subscriptions->count() == 0)
            <p class="text-center">No subscriptions found.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover text-center table-bordered" style="background-color: #fff; width: 100%; max-width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">S.No</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Subscription Number</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Company Name</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Plan Name</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Plan Amount</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Start Date</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">End Date</th>
                            <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $index => $subscription)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subscription->subscription_number }}</td>
                                <td>{{ $customer->company_name}}</td>
                                <td>{{ $subscription->plan_name }}</td>
                                <td>{{ number_format($subscription->plan_price, 2) }}</td>
                                <td>{{ $subscription->start_date }}</td>
                                <td>{{ $subscription->next_billing_at }}</td>
                                <td>
                                    @if(strtolower($subscription->status) == 'success')
                                        <span class="badge bg-success">Success</span>
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

 <!-- Invoices Section -->
 <div id="invoices" class="mt-4" style="display: none;">
        @if($invoices->count() == 0)
            <p class="text-center">No invoices found.</p>
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
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $payments->payment_mode ?? 'N/A' }}</td>
                                <td>{{ $customer->company_namee ?? 'N/A' }}</td>
                                <td>{{ $invoice->plan_name ?? 'N/A' }}</td>
                                <td>{{ number_format($invoice->plan_price, 2) }}</td>
                                <td>{{ number_format($invoice->invoice_price ?? 0, 2) }}</td>
                                <td>{{ number_format($invoice->credits_applied, 2) }}</td>
                                <td>{{ number_format($invoice->payment_made, 2) }}</td>
                                <td>
                                    @if(strtolower($invoice->status) == 'paid')
                                        <span class="badge bg-success">Paid</span>
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
<script>
    function showSection(sectionId) {
        document.getElementById('overview').style.display = 'none';
        document.getElementById('subscriptions').style.display = 'none';
        document.getElementById('invoices').style.display = 'none';
        document.getElementById(sectionId).style.display = 'block';

        // Remove active class from all tabs
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));

        // Add active class to the selected tab
        event.target.classList.add('active');
    }
</script>
</div>
@endsection