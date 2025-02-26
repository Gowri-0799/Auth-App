@extends("layouts.admin")
@section('title', "My Subscriptions")
@section('content')
<div id="content" style="box-sizing: border-box;" class="p-3">
   <div class="d-flex justify-content-center align-items-center flex-wrap">
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
    
    <div style="width:100%;" class="row mb-0 border shadow">
    @if(!$subscriptions || !$plans)
    <div style="flex: 1; padding: 50px; background-color: #eaf1fc; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; justify-content: center; 
    align-items: center; text-align: center; min-height: 300px;" class="w-100">
        <h3 style="font-size: 24px; color: #333;">No Subscription Found</h3>
    </div>
    @else
    
    <div class="col-lg border-0 bg-clearlink p-3">
        <div class="d-flex flex-wrap justify-content-between align-items-stretch">
        <div style="display: flex; flex-wrap: wrap; width: 100%;">
        {{-- Left Card with Subscription Details --}}
@if($subscriptions->status == 'Cancelled')
    <div style="flex: 1; padding: 20px; background-color: #eaf1fc; 
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); min-width: 320px; position: relative; display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 100%;">
        
        {{-- Plan Name (Override for Canceled Subscriptions) --}}
        <h3 class="text-primary fw-bold mb-3 m-0 text-uppercase p-0">
                                        <strong>Free Monthly</strong>
                                    </h3>

           <h4 class="">{{ $subscriptions->subscription_number }}</h4>

        {{-- Plan Price (Override for Canceled Subscriptions) --}}
        <span style="font-size: 30px; font-weight: bold; color: #000;">
            US $0.00
        </span><br>

        {{-- Status Badge --}}
        <span class="sub-status p-1 px-1 w-25 mt-3 badge-fail fs-5">
            <strong>Cancelled</strong>
        </span>
    </div>
@else
            {{-- Left: Subscription Details Card --}}
            <div style="flex: 1; padding: 20px; background-color: #eaf1fc; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); min-width: 320px; position: relative; display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 100%;">
                <h3 class="text-primary fw-bold mb-3 m-0 text-uppercase p-0">{{ $plans->plan_name }}</h3>
                <h4 class="">{{ $subscriptions->subscription_number }}</h4>
                <div class="d-flex flex-row align-items-start">
                    <p>US&nbsp;$</p>
                    <h4><strong>{{ number_format($plans->plan_price, 2) }}</strong></h4>
                </div>
                
                <span class="sub-status position-absolute top-0 end-0 p-1 px-3 badge-success mt-3 fs-5">
                    <strong>{{ ucfirst($subscriptions->status) }}</strong>
                </span>
                
                {{-- Action Buttons --}}
                <div class="d-flex flex-row mt-2">
                @if($subscriptions->addon == 1)
                    <p class="mt-3 w-50 text-dark">You have also Subscribed to: <span>{{$plans->addon_code}}</span> for the current month</p>
                @else 
                    <a style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('addon-preview-form').submit();" class="btn btn-primary my-3 me-5 justify-content-center d-flex align-items-center rounded w-50">
                        Monthly Click Add-On
                    </a>
                    <form id="addon-preview-form" action="{{ route('addon.preview') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="plan_code" value="{{ $plans->plan_code }}">
                    </form>
                @endif
                    
                    @if(!$upgradePlans->isEmpty())
                        <a id="upgrade-button" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#upgradeModal" 
                           class="btn btn-primary m-3 d-flex align-items-center w-50 justify-content-center rounded p-2">Upgrade</a>
                    @else
                        <a class="btn btn-primary m-3 d-flex align-items-center w-50 justify-content-center rounded p-2" 
                           data-bs-toggle="modal" data-bs-target="#contactModal">
                            Contact Us
                        </a>
                    @endif
                </div>
                
                {{-- Cancellation and Downgrade --}}
                <div class="d-flex flex-row">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#cancelSubscription" 
                       class="text-decoration-underline text-primary my-3 me-5 w-50">
                        Cancellation
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#downgradeModal"  
                    class="text-decoration-underline text-primary my-3 w-50">
                        Downgrade
                    </a>
                </div>
                
                {{-- Next Renewal Date --}}
                <p class="mt-2 fw-normal">Next Renewal Date: 
                    {{ \Carbon\Carbon::parse($subscriptions->next_billing_at)->format('d-M-Y') }}
                </p>
            </div>
            @endif  
            {{-- Right: Payment Method Card --}}
            <div class="col-lg d-flex justify-content-center flex-column shadow align-items-center" 
                 style="background-color: transparent; border: none;">
                <div class="credit-card acct">
                    <div class="card-details">
                        <div class="card-number">
                            XXXX XXXX XXXX {{ substr($subscriptions->card_last4, -4) }}
                        </div>
                        <div class="card-expiry">
                            <div class="card-name text-uppercase">CARD</div>
                        </div>
                    </div>
                    <div class="expiry">
                        <p class="ms-2 m-0 d-inline p-0">
                            <small>VALID TILL {{ date('m/Y', strtotime($subscriptions->card_expiry)) }}</small>
                        </p>
                    </div>
                </div>
                <a href="/payments/{{$subscriptions->zoho_cust_id}}" class="text-decoration-underline text-primary mt-2">
                    Update Card Details
                </a>
                <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#addnewPaymentModal" 
                   class="text-decoration-underline text-primary mt-2 cursor-pointer">
                   Add New Payment Method
                </a> -->
                <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#switchPaymentModal" 
                   class="text-decoration-underline text-primary mt-2 cursor-pointer">
                   Switch Payment Method
                </a> -->
            </div>
        </div>
    </div>
    @endif

    {{-- FAQ Section --}}
    <div class="tableTerms p-0 border-0 mb-5 margin-default">
        <p class="fw-bold ms-3">
            <h2 class="fw-bold ms-2">FAQ's</h2>
        </p>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Billing & Payments
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul>
                            <li>All billing & payments will be upfront in US Dollars</li>
                            <li>Plans are billed on a calendar billing schedule</li>
                            <li>Associated Credit Card will be charged on renewal of a plan subscription on the first of each month</li>
                            <li>Plan price and subscription will be pro-rata or full as applicable on some upgrades</li>
                            <li>Any credits which arise in the process of upgrades will be kept for adjusting subsequent subscription renewals and will not be refunded</li>
                            <li>If a subscription renewal auto-debit of the card fails, you will be notified to pay by email (invoice link) or pay through the payment portal</li>
                            <li>If the maximum number of clicks is reached prior to the end of the month, the plan can be upgraded or a Monthly Add-On can be purchased.</li>
                            <li>Only one Monthly Add-On can be purchased per month</li>
                            <li>Clearlink does not offer refunds on a paid subscription or credits if any, but will consider specific cases of erroneous and duplicate payment transactions after review</li>
                            <li>Clearlink reserves the right to change the product, plan, subscription, limits, name and price, and will send notification of changes</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Upgrade / Downgrade / Cancel
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul>
                            <li>You can upgrade a paid plan at any time, the upgrade will be applied immediately on successful payment</li>
                            <li>Plan downgrade requests can be sent through a support ticket and will be done only at the end of the current subscription period</li>
                            <li>Subscription cancellation requests can be sent through a support ticket</li>
                            <li>Unpaid subscriptions will be automatically cancelled after 15 days</li>
                            <li>Plan Features & Limits are subject to change based on upgrade / downgrade process</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        Security / Privacy
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul>
                            <li>Clearlink uses Zoho to manage your subscriptions / support and uses Stripe as a secured payment gateway</li>
                            <li>Clearlink respects and protects your data as per the <a target="_blank" href="https://www.clearlink.com/privacy/">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>         
                           
      </div>
   </div>
</div>

<!-- {{-- Modal for Upgrade Plan --}} -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-popup">
            <div class="modal-header">
                <h3 class="modal-title" id="upgradeModalLabel">Upgrade Plans</h3>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="upgradeForm" action="{{ route('upgrade.preview') }}" method="GET">
                    @csrf
                    <div class="mb-3">
                        <label for="upgradeSelect" class="form-label">Select an Upgrade Plan</label>
                        <div class="d-flex flex-column">
                            <select id="upgradeSelect" name="plan_id" class="mt-4 form-select-lg border-dark shadow-none" required style="width:300px;">
                                <option class="py-3" value="" disabled selected>Select a Plan</option>
                                @foreach($upgradePlans as $upgradePlan)
                                <option class="py-3" value="{{ $upgradePlan->plan_id ?? '' }}">
                                    {{ $upgradePlan->plan_code }} 
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

<!-- {{-- Modal for Downgrade Plan --}} -->
<div class="modal fade" id="downgradeModal" tabindex="-1" aria-labelledby="downgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-popup">
            <div class="modal-header">
                <h3 class="modal-title" id="downgradeModalLabel">Select a plan to downgrade</h3>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="downgradeForm" action="{{ route('downgrade_plan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="d-flex flex-column">
                            <select id="downgradeSelect" name="plan_id" class="mt-4 form-select-lg border-dark shadow-none" required style="width:300px;">
                                <option class="py-3" value="" disabled selected>Select a Plan</option>
                                @foreach($downgradePlans as $downgradePlan)
                                <option class="py-3" value="{{ $downgradePlan->plan_id }}">
                                    {{ $downgradePlan->plan_name }} 
                                </option>
                                @endforeach
                            </select>
                            <input type="submit" class="mt-5 w-25 btn btn-primary rounded" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal contact us -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content terms-title bg-popup">
            <div class="modal-header border-0">
                <h3 class="modal-title" id="contactModalLabel">Contact Us</h3>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{ route('custom.enterprise') }}" method="post">
                    @csrf
                    <textarea class="w-100 p-3 pe-4 border-0 rounded" name="message" rows="5">
I am interested in learning more about the Enterprise plan. Please contact me with more information.
                    </textarea>
                    <input type="submit" class="btn btn-primary popup-element " value="Send">
                </form>
            </div>
            <div class="modal-footer border-0"></div>
        </div>
    </div>
</div>

<!-- Modal for Cancel Subscription -->
<div class="modal fade" id="cancelSubscription" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-popup">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Do you really want to cancel the subscription?</h1>
        <button type="button" class="close border-0 mb-4" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark fs-3"></i>
        </button>
      </div>
      <div class="modal-footer">
        <form action="{{ route('cancel.subscription') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Proceed</button>
        </form>
        <button type="button" data-bs-dismiss="modal" class="btn button-clearlink text-primary fw-bold">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
        });
    }, 5000);

    var statusSpan = document.getElementById('status-span');
    if(statusSpan) {
      var textLength = statusSpan.textContent.trim().length;
      statusSpan.style.width = (textLength * 12) + 'px';
    }
</script>
@endsection
