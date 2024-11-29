@extends("layouts.default")
@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px;" class="p-3">
   <!-- Top Navigation Tabs -->
   <div class="d-flex justify-content-between align-items-center my-3">
      <h3>{{ $customer->company_name }}</h3>
   </div>
   <!-- Navigation Tabs with Active Class based on Section -->
   <ul class="nav nav-tabs">
      <li class="nav-item">
         <a class="nav-link {{ $selectedSection === 'overview' ? 'active' : '' }}" 
            href="{{ route('customers.show', $customer->zohocust_id) }}?section=overview">Overview</a>
      </li>
      <li class="nav-item">
         <a class="nav-link {{ $selectedSection === 'subscriptions' ? 'active' : '' }}" 
            href="{{ route('customers.show', $customer->zohocust_id) }}?section=subscriptions">Subscriptions</a>
      </li>
      <li class="nav-item">
         <a class="nav-link {{ $selectedSection === 'invoices' ? 'active' : '' }}" 
            href="{{ route('customers.show', $customer->zohocust_id) }}?section=invoices">Invoices</a>
      </li>
      <li class="nav-item">
         <a class="nav-link {{ $selectedSection === 'creditnote' ? 'active' : '' }}" 
            href="{{ route('customers.show', $customer->zohocust_id) }}?section=creditnote">Credit Notes</a>
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
   <div id="overview" class="row mt-4" style="{{ $selectedSection !== 'overview' ? 'display: none;' : '' }}">
      <h4 class="mb-4">Overview</h4>
      <!-- customer Section -->
      <div class="col-lg-6">
         <div class="card w-100 border-0 bg-clearlink rounded mb-3">
            <div class="card-body">
               <p class="m-0">
                  <i class="fa fa-building right-margin text-primary" aria-hidden="true"></i>
                  <strong>{{ $customer->company_name }}</strong>
               </p>
               <p class="m-0">
                  <i class="fa fa-user right-margin text-primary" aria-hidden="true"></i>
                  <strong>{{ $customer->customer_name }}</strong>
               </p>
               <p class="m-0">
                  <!-- <i class="fa fa-user right-margin text-primary" aria-hidden="true"></i> -->
                  <strong>{{ $customer->email }}</strong>
               </p>
               <h5 class="mt-4"><strong>Affiliate IDs:</strong></h5>
               <ul>
                  @foreach($affiliates as $affiliate)
                  <li>{{ $affiliate->isp_affiliate_id }} ({{ $affiliate->domain_name }})</li>
                  @endforeach
               </ul>
               <h5 class="mt-4"><strong>Address Details:</strong></h5>
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
            <div class="card-body right-margin">
               <div class="d-flex flex-row mb-5 justify-content-between">
                  <h4 class="ms-3">Users</h4>
                  <a data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-primary btn-sm me-3">Invite User</a>
               </div>
               @if($partnerUser->count() == 0)
               <div class="d-flex justify-content-center align-items-center"> No secondary users found </div>
               @else
               @foreach($partnerUser as $user)
               <div class="d-flex flex-row mb-4">
                  <div class="col-lg-1 user-icon">
                     <i style="font-size: 44px;" class="fa-solid fa-circle-user text-primary"></i>
                  </div>
                  <div class="col-lg-9 ms-3">
                     <p class="p-0 m-0">
                        <strong>
                        {{ $user->first_name }}&nbsp;{{ $user->last_name }}
                        
                        </strong>
                     </p>
                     <p class="p-0 m-0">{{ $user->email ??'' }}</p>
                  </div>
               </div>
               <hr class="borders-clearlink">
               @endforeach
               @endif
            </div>
         </div>
      </div>
   </div>
   <!-- Subscriptions Section -->
   <div id="subscriptions" class="section mt-4" style="{{ $selectedSection !== 'subscriptions' ? 'display: none;' : '' }}">
      <!-- Filter Form -->
      <form method="GET" action="{{ route('nav.subscriptions.filter') }}" class="row mb-4 align-items-end">
         @include('partials.filter-form')
         <input type="hidden" name="zohocust_id" value="{{ $customer->zohocust_id }}">
         <input type="hidden" name="section" value="subscriptions"> <!-- Pass the selected section with the form -->
      </form>
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
                                            @if(strtolower($subscription->status) == 'live')
                                                <span class="badge badge-success">Live</span>
                                             @elseif(strtolower($subscription->status) == 'cancelled')
                                                <span class="badge badge-fail">Cancelled</span>  
                                            @else
                                                <span class="badge badge-fail">Pending</span>
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
   <div id="invoices" class="section mt-4" style="{{ $selectedSection !== 'invoices' ? 'display: none;' : '' }}">
      <!-- Filter Form for Invoices -->
      <form method="GET" action="{{ route('nav.invoice.filter') }}" class="row mb-4 align-items-end">
         @include('partials.filter-form')
         <input type="hidden" name="zohocust_id" value="{{ $customer->zohocust_id }}">
         <input type="hidden" name="section" value="invoices">
      </form>
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
                  <td> @php                                    
                     $paymentDetailsArray = json_decode($invoice->payment_details, true);                                    
                     $paymentMode = $paymentDetailsArray && isset($paymentDetailsArray[0]['payment_mode']) 
                     ? $paymentDetailsArray[0]['payment_mode'] 
                     : 'N/A';
                     @endphp
                     {{ $paymentMode }}
                  </td>
                  <td>{{ $customer->company_name ?? 'N/A' }}</td>
                  <td>
                     @php                                    
                     $invoiceItemsArray = json_decode($invoice->invoice_items, true);                                    
                     $planName = $invoiceItemsArray && isset($invoiceItemsArray[0]['code']) 
                     ? $invoiceItemsArray[0]['code'] 
                     : 'N/A';
                     @endphp
                     {{ $planName }}
                  </td>
                  <td>
                     @php                                    
                     $planPrice = $invoiceItemsArray && isset($invoiceItemsArray[0]['price']) 
                     ? number_format($invoiceItemsArray[0]['price'], 2) 
                     : '0.00';
                     @endphp
                     {{ $planPrice }}
                  </td>
                  <td>{{ number_format($invoice->invoice_price ?? 0, 2) }}</td>
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
   <!-- Credit Notes Section -->
   <div id="creditnote" class="section mt-4" style="{{ $selectedSection !== 'creditnote' ? 'display: none;' : '' }}">
      <!-- Filter Form for Credit Notes -->
      <form method="GET" action="{{ route('nav.creditnote.filter') }}" class="row mb-4 align-items-end">
         @include('partials.filter-form')
         <input type="hidden" name="zohocust_id" value="{{ $customer->zohocust_id }}">
         <input type="hidden" name="section" value="creditnote">
      </form>
      @if($creditnotes->count() == 0)
      <p class="text-center">No credit notes found.</p>
      @else
      <div class="table-responsive">
         <table class="table table-hover text-center table-bordered" style="background-color:#fff; width: 100%; max-width: 100%;">
            <thead class="table-light">
               <tr>
                  <th style="background-color:#EEF3FB;">#</th>
                  <th style="background-color:#EEF3FB;"> Date</th>
                  <th style="background-color:#EEF3FB;">Credit Note #</th>
                  <th style="background-color:#EEF3FB;">Company Name</th>
                  <th style="background-color:#EEF3FB;">Invoice Number</th>
                  <th style="background-color:#EEF3FB;">Credited Amount (USD)</th>
                  <th style="background-color:#EEF3FB;">Balance (USD)</th>
                  <th style="background-color:#EEF3FB;">Status</th>
                  <th style="background-color:#EEF3FB;">View</th>
               </tr>
            </thead>
            <tbody>
               @foreach($creditnotes as $index => $creditnote)
               <tr>
                  <td>{{ (int)$index + 1 }}</td>
                  <td>{{ \Carbon\Carbon::parse($creditnote->credited_date)->format('d-M-Y') }}</td>
                  <td>{{ $creditnote->creditnote_number }}</td>
                  <td>{{ $customer->company_name }}</td>
                  <td>{{ $creditnote->invoice_number }}</td>
                  <td>{{ number_format($creditnote->credited_amount, 2) }}</td>
                  <td>{{ number_format($creditnote->balance, 2) }}</td>
                  <td class="p-2 status">
                     @if(strtolower($creditnote->status) == 'credited')
                     <span class="badge-success">Open</span>
                     @else
                     <span class="badge-fail">Closed</span>
                     @endif
                  </td>
                  <td>
                     <a href="{{ route('pdf.download', $creditnote->creditnote_id) }}"  class="btn btn-sm btn-primary">
                     Download PDF
                     </a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
      @endif
   </div>
</div>
<!-- Invite User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
      <div class="modal-content">
         <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">Invite User </h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form action="{{ route('invite-user') }}" method="POST">
               @csrf
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <div class="mb-3 row">
                  <div class="col-lg">
                     <input name="first_name" class="ms-2 form-control" placeholder="First Name*" required>
                  </div>
                  <div class="col-lg">
                     <input name="last_name" class="ms-2 form-control" placeholder="Last Name*" required>
                  </div>
               </div>
               <div class="mb-3 row">
                  <div class="col-lg">
                     <input name="email" class="ms-2 form-control" placeholder="Email*" required>
                  </div>
                  <div class="col-lg">
                     <input name="phone_number" class="ms-2 form-control" placeholder="Phone Number*" required>
                  </div>
               </div>
               <input name="zoho_cust_id" value="{{ $customer->zohocust_id }}" type="hidden" />
               <input type="submit" class="btn btn-primary text-white px-3 py-2 rounded" value="Save Changes">
            </form>
         </div>
      </div>
   </div>
</div>
<script>
   function showSection(sectionId) {
       document.getElementById('overview').style.display = 'none';
       document.getElementById('subscriptions').style.display = 'none';
       document.getElementById('invoices').style.display = 'none';
       document.getElementById('creditnote').style.display = 'none';
       document.getElementById(sectionId).style.display = 'block';
   
       // Remove active class from all tabs
       document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
   
       // Add active class to the selected tab
       event.target.classList.add('active');
   }
   
    function handleResetClick(event) {
        event.preventDefault(); // Prevent the default link behavior
        const url = window.location.href.split('?')[0]; // Get the base URL without query parameters
        window.location.href = url; // Redirect to the base URL to reset filters
    }
   
</script>
@endsection