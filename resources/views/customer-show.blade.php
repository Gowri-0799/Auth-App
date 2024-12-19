@extends("layouts.default")
@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px;" class="p-3">
   <!-- Top Navigation Tabs -->
   <div class="d-flex justify-content-between align-items-center my-3">
      <h3>{{ $customer->company_name }} 
      <span class="badge" style="background-color: #D4EDDA; color: #155724; padding: 5px 10px;">
      {{ $customer->status }}                     
    </span> 
</h3>
   </div>
   {{-- Alert Messages --}}
    <div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 999; width: 300px;">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> 
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
<!--Navigation section-->
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
      <li class="nav-item" >
         <a class="nav-link {{ $selectedSection === 'providerdata' ? 'active' : '' }}"
          href="{{ route('customers.show', $customer->zohocust_id) }}?section=providerdata">Provider Data</a>
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
            <div class="d-flex justify-content-between align-items-center">
               <!-- Customer Name Section -->
               <div>
                  <i class="fa fa-building right-margin text-primary" aria-hidden="true"></i>
                  <strong>{{ $customer->company_name }}</strong>
               </div>
           
@if($partnerUsers->status === 'inactive')
   
    <form id="markAsActiveForm" method="POST" action="{{ route('customer.markActive', $customer->zohocust_id) }}">
    @csrf
        <button type="submit" class="btn btn-light p-1 border-0 custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title=" Mark as active">
        <i class="fa fa-user text-primary" aria-hidden="true"></i>
        </button>
        </form>
@elseif($partnerUsers->status === 'active' || $partnerUsers->status === null)
 
    <form id="markAsInactiveForm" method="POST" action="{{ route('customer.markInactive', $customer->zohocust_id) }}">
        @csrf
        <button type="submit" class="btn btn-light p-1 border-0 custom-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark as Inactive">
           
            <i class="fa fa-user-slash text-primary" aria-hidden="true"></i> 
        </button>
    </form>
@endif
            </div>
            
            <!-- Customer Name -->
            <p class="m-0 mt-2">
               <i class="fa fa-user right-margin text-primary" aria-hidden="true"></i>
               <strong>{{ $customer->customer_name }}</strong>
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

            <!-- Primary User Section -->
            <div class="d-flex flex-row mb-4">
                <div class="col-lg-1 user-icon">
                    <i style="font-size: 44px;" class="fa-solid fa-circle-user text-primary"></i>
                </div>
                <div class="col-lg-9 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="p-0 m-0 me-2">
                            <strong>{{ $customer->company_name }} (Primary)</strong>
                        </p>
                        <form action="{{ route('resend.invite') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="email" value="{{ $customer->email}}">
    <button type="submit" 
    class="btn button-clearlink text-primary fw-bold btn-sm resend-invite" >Resend invite</button>
</form>
                          </div>
                    <p class="p-0 m-0">{{ $customer->email }}</p>
                </div>
            </div>

            <!-- Secondary Users Section -->
            @if($partnerUser->count() == 0)
                <div class="d-flex justify-content-center align-items-center">No secondary users found</div>
            @else
                @foreach($partnerUser as $user)
                <div class="d-flex flex-row mb-4">
                    <div class="col-lg-1 user-icon">
                        <i style="font-size: 44px;" class="fa-solid fa-circle-user text-primary"></i>
                    </div>
                    <div class="col-lg-9 ms-3">
                        <div class="d-flex align-items-center">
                            <p class="p-0 m-0 me-2">
                                <strong>{{ $user->first_name }}&nbsp;{{ $user->last_name }}</strong>
                            </p>
                           
<form action="{{ route('resend.invite') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="email" value="{{ $user->email }}">
    <button type="submit" 
    class="btn button-clearlink text-primary fw-bold btn-sm resend-invite" >Resend invite</button>
</form>
                        </div>
                        <p class="p-0 m-0">{{ $user->email ?? '' }}</p>
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
       

      <div class="d-flex justify-content-between align-items-center mb-3">
         <!-- Title -->
         <div>
            <span style="font-family: Arial, sans-serif; font-size: 21px; font-weight: bold;">Subscription</span>
         </div>
         <!-- "+" Icon and Text -->
         <div class="d-flex align-items-center">
         <a href="#"
   class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center create-subscription-btn" 
   style="width: 40px; height: 40px; margin-right: 10px;">
   <i class="fas fa-plus"></i>
</a>
    @if($subscriptions->count() == 0)
        <span style="font-family: Arial, sans-serif; font-size: 16px; margin-right: 170px;">Create Subscription</span>
    @else
        <span style="font-family: Arial, sans-serif; font-size: 16px; margin-right: 170px;">Upgrade Subscription</span>
    @endif
</div>
</div>
      @if($subscriptions->count() == 0)
      <!-- Centered "No Subscriptions" Message -->
      <div class="d-flex justify-content-center align-items-center" 
         style="height: 150px; width: 90%; margin-left: auto; margin-right: auto; border: 1px solid #ddd; border-radius: 5px;">
         <div class="text-muted" style="font-family: Arial, sans-serif; font-size: 18px;">No Subscriptions Made</div>
      </div>
      @else
      <!-- Filter Form (appears only if subscriptions exist) -->
      <form method="GET" action="{{ route('nav.subscriptions.filter') }}" class="row mb-4 align-items-end">
         @include('partials.filter-form')
         <input type="hidden" name="zohocust_id" value="{{ $customer->zohocust_id }}">
         <input type="hidden" name="section" value="subscriptions"> <!-- Pass the selected section with the form -->
      </form>
      <!-- Subscriptions Table -->
      <div class="table-responsive">
         <table class="table table-hover text-center table-bordered" style="background-color: #fff; width: 100%; max-width: 100%;">
            <thead class="table-light">
               <tr>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">S.No</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Subscription Number</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Company Name</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Plan Name</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Plan Amount</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Start Date</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">End Date</th>
                  <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Status</th>
               </tr>
            </thead>
            <tbody>
               @foreach($subscriptions as $index => $subscription)
               <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $subscription->subscription_number }}</td>
                  <td>{{ $customer->company_name }}</td>
                  <td>{{ $subscription->plan_name }}</td>
                  <td>{{ number_format($subscription->plan_price, 2) }}</td>
                  <td>{{ $subscription->start_date }}</td>
                  <td>{{ $subscription->next_billing_at }}</td>
                  <td>
                     @if(strtolower($subscription->status) == 'live')
                     <span class="badge badge-success">Live</span>
                     @elseif(strtolower($subscription->status) == 'cancelled')
                     <span class="badge badge-danger">Cancelled</span>
                     @else
                     <span class="badge badge-warning">Pending</span>
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
   <div>
            <span style="font-family: Arial, sans-serif; font-size: 21px; font-weight: bold;">Invoice</span>
         </div><br>
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
   <div>
            <span style="font-family: Arial, sans-serif; font-size: 21px; font-weight: bold;">Credit notes</span>
         </div><br>
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
<!-- provider data Section -->
<!-- Provider Data Section -->
<div id="providerdata" class="section mt-4" style="{{ $selectedSection !== 'providerdata' ? 'display: none;' : '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Title -->
        <div>
            <span style="font-family: Arial, sans-serif; font-size: 21px; font-weight: bold;">Company Info</span>
        </div>
        
        <!-- Button for Upload or Send -->
        <div class="d-flex align-items-center">
        @if(!$companyInfo)
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadCompanyInfoModal">
              Upload Company Info
            </a>
            @else
                <a href="#" class="btn btn-primary">
                    Send Details to Admin
                </a>
            @endif
        </div>
    </div>

    <!-- Company Info Table -->
    <div class="table-responsive">
    @if(!$companyInfo)
        <div class="text-center p-3 border" style="width: 70%; height: 100px; margin: 0 auto;">No data found</div>
        @else
        <table class="table" style="border-collapse: collapse; width: 100%; border: 1px solid black;">
    <thead>
        <tr style="border-bottom: 2px solid black;">
            <th style="font-weight: bold; text-align: center;">Logo Image</th>
            <th style="font-weight: bold; text-align: center;">Landing Page URL</th>
            <th style="font-weight: bold; text-align: center;">Landing Page URL (Spanish)</th>
            <th style="font-weight: bold; text-align: center;">Company Name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border-top: 1px solid black;">       
                <img src="{{ asset('storage/' . $companyInfo->logo_image ?? '') }}" alt="logo" style="width:110px;" id="logoPreview">
            </td>
            <td style="border-top: 1px solid black; text-align: center;"><a href="{{ $companyInfo->landing_page_uri }}" target="_blank">{{ $companyInfo->landing_page_uri }}</a></td>
            <td style="border-top: 1px solid black; text-align: center;"><a href="{{ $companyInfo->landing_page_url_spanish }}" target="_blank">{{ $companyInfo->landing_page_url_spanish }}</a></td>
            <td style="border-top: 1px solid black; text-align: center;">{{ $companyInfo->company_name }}</td>
        </tr>
    </tbody>
</table>

        @endif
    </div>


<!-- Provider Data Table -->
<div class="mt-4">
    <span style="font-family: Arial, sans-serif; font-size: 21px; font-weight: bold;">Provider Data</span><br><br>

    <!-- Button aligned to the right -->
    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadProviderModal" style="float: right; margin-top: 10px;">Upload Provider Data</a>

    <form method="GET" action="{{ route('nav.provider.filter') }}" class="row mb-4 align-items-end">
        @include('partials.filter-form')
        <input type="hidden" name="zohocust_id" value="{{ $customer->zohocust_id }}">
        <input type="hidden" name="section" value="creditnote">
    </form>
</div>

<div class="table-responsive">
    @if($providerData && $providerData->count() > 0)
    <table class="table" style="border-collapse: collapse; width: 90%; margin: 0 auto; border: 1px solid black;">
        <thead>
            <tr>
                <th style="font-weight: bold; text-align: center;">Date</th>
                <th style="font-weight: bold; text-align: center;">File Name</th>
                <th style="font-weight: bold; text-align: center;">File Size</th>
                <th style="font-weight: bold; text-align: center;">ZIP Count</th>
                <th style="font-weight: bold; text-align: center;">CSV File URL</th>
            </tr>
        </thead>
        <tbody style="border-top: 2px solid #000; border-bottom: 2px solid #000;">
            @foreach($providerData as $data)
            <tr>
                <td style="border-left: none; border-right: none; text-align: center;">
                {{ optional($data)->created_at ? \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') : 'No date available' }}
                </td>
                <td style="border-left: none; border-right: none; text-align: center;">{{ $data->file_name ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $data->file_size ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $data->zip_count ?? 'N/A' }}</td>
                <td style="text-align: center;">
                    @if(!empty($data->url))
                    <a href="{{ asset('storage/' . $data->url) }}" download>Download</a>
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
   
    @else
    <p style="text-align: center; font-size: 18px;">No provider data found.</p>
    @endif
</div>

<!--end nav div-->
</div> 
<!-- Create subscription model-->
<div class="modal fade" id="downgradeModal" tabindex="-1" aria-labelledby="downgradeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-popup">
         <div class="modal-header">
            <h3 class="modal-title" id="downgradeModalLabel">Enter the required details</h3>
            <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark fs-3"></i>
            </button>
         </div>
         <div class="modal-body">
            <form id="downgradeForm" action="{{ route('subscribelink') }}" method="POST">
               @csrf
               <div class="mb-3">
                  <div class="d-flex flex-column">
                     <h3 class="modal-title" id="downgradeModalLabel">Plans</h3>
                     @if($plans->count() > 0)
                     <select id="downgradeSelect" name="plan_id" class="mt-4 form-select-lg border-dark shadow-none" required="" style="width:300px;">
                        <option class="py-3" value="" disabled selected>Select a Plan</option>
                        @foreach($plans as $plan)
                        <option class="py-3" value="{{ $plan->plan_code }}">
                           {{ $plan->plan_name }} - ${{ number_format($plan->plan_price, 2) }}
                        </option>
                        @endforeach
                     </select>
                     @endif
                     <input type="submit" class="mt-5 w-25 btn btn-primary rounded" value="Submit">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<!-- Upgrade subscription-->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-popup">
            <div class="modal-header">
                <h3 class="modal-title" id="upgradeModalLabel">Upgrade Plans</h3>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark fs-3"></i></button>
            </div>
            <div class="modal-body">
                <form id="upgradeForm" action="{{ route('upgradelink') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="upgradeSelect" class="form-label">Select an Upgrade Plan</label>
                        <div class="d-flex flex-column">
                            <select id="upgradeSelect" name="plan_id" class="mt-4 form-select-lg border-dark shadow-none" required style="width:300px;">
                                <option class="py-3" value="" disabled selected>Select a Plan</option>
                                @foreach($upgradePlans as $upgradePlan)
                                <option class="py-3" value="{{ $upgradePlan->plan_id ?? '' }}">
                                    {{ $upgradePlan->plan_code }} - ${{ $upgradePlan->plan_price }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="plan_code" value="{{$upgradePlan->plan_code ?? ''}}">
                            <input type="submit" class="mt-5 w-25 btn btn-primary rounded" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Alert Message Modal -->
<div class="modal fade" id="firstLoginModal" tabindex="-1" aria-labelledby="firstLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="firstLoginModalLabel">Incomplete Customer Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                The customer needs to add provider data and company info fields before creating a subscription.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal for upload company info -->
<div class="modal fade" id="uploadCompanyInfoModal" tabindex="-1" aria-labelledby="uploadCompanyInfoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #e6f7ff;"> 
            <div class="modal-header">
                <h5 class="modal-title" id="uploadCompanyInfoLabel">Enter the required details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadCompanyInfoForm" action="{{ route('company-info.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="logo" class="form-label" style="font-weight: bold; color: black;">Logo*</label>
                        <input type="file" class="form-control" accept="image/*" name="logo" >
                    </div>
                    <div class="mb-3">
                        <label for="landingPageUrl" class="form-label" style="font-weight: bold; color: black;">Landing Page Url*</label>
                        <input type="url" class="form-control" name="landing_page_url" id="landingPageUrl" placeholder="Enter landing page URL">
                    </div>
                    <div class="mb-3">
                        <label for="landingPageUrlSpanish" class="form-label" style="font-weight: bold; color: black;">Landing Page Url (Spanish)</label>
                        <input type="url" class="form-control" name="landing_page_url_spanish" id="landingPageUrlSpanish" placeholder="Enter landing page URL in Spanish">
                    </div>
                    <div class="mb-3">
                        <label for="companyName" class="form-label" style="font-weight: bold; color: black;">Company Name*</label>
                        <input type="text" class="form-control" name="company_name" value="{{ $customer->company_name ?? '' }}" required>
                    </div>
                    <button type="button" class="btn btn-primary" style="width: auto;" id="submitCompanyInfoButton">Upload Company Info</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for provider data-->
<div class="modal fade" id="uploadProviderModal" tabindex="-1" aria-labelledby="uploadProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #e3f2fd; border-radius: 8px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="uploadProviderModalLabel">Enter the required details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to upload CSV -->
                <form id="providerDataForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.provider-data.upload') }}">
                  @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label fw-bold">Choose file</label>
                        <input class="form-control" type="file" name="csv_file" id="csvFileInput" accept=".csv">
                    </div>
                    <div class="d-grid">
                        <!-- This button now triggers the form submission -->
                        <button type="submit" class="btn btn-primary" style="width: auto;">Upload Provider Data</button>
                    </div>
                </form>
            </div>
        </div>
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
       document.getElementById('providerdata').style.display = 'none';
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

    document.addEventListener('DOMContentLoaded', function () {
        const createSubscriptionBtn = document.querySelector('.create-subscription-btn');
        createSubscriptionBtn.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default action
            const firstLogin = @json($customer->first_login); // Pass PHP variable to JS
            
            if (firstLogin === 1) {
                // Show the first login modal
                const modal = new bootstrap.Modal(document.getElementById('firstLoginModal'));
                modal.show();
            } else {
                // Proceed to show the subscription modal
                const modalId = '{{ $subscriptions->count() == 0 ? '#downgradeModal' : '#upgradeModal' }}';
                const modal = new bootstrap.Modal(document.querySelector(modalId));
                modal.show();
            }
        });

        document.getElementById('submitCompanyInfoButton').addEventListener('click', function() {
        // Submit the form when the button is clicked
        document.getElementById('uploadCompanyInfoForm').submit();
    });
    });
   

</script>
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endsection