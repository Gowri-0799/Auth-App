
@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:100%" class="p-3">
    <!-- Add Modal for First Login Alert -->
<div class="modal fade" id="showAlertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-popup">
            <div class="modal-header d-flex justify-content-end border-0 bg-popup">
                <button type="button" class="close border-0 bg-popup" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid text-dark fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body mb-5">
                <h3 class="message">Please complete the following to create a Subscription</h3>
                <ul class="message">
                    <li class="d-flex justify-content-between">
                        <span>Upload Logo (Company Info)</span>
                        <!-- Show tick if logo_image is not null -->
                        @if ($companyInfo && $companyInfo->logo_image)
                            <i class="fa-solid fa-check text-check fs-3"></i>
                        @endif
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Add Company Name (Company Info)</span>
                        <!-- Show tick if company_name is not null -->
                        @if ($companyInfo && $companyInfo->company_name)
                            <i class="fa-solid fa-check text-check fs-3"></i>   
                        @endif
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Set Landing Page URL (Company Info)</span>
                        <!-- Show tick if landing_page_uri is not null -->
                        @if ($companyInfo && $companyInfo->landing_page_uri)
                            <i class="fa-solid fa-check text-check fs-3"></i>
                        @endif
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Upload Provider Data</span>
                        <!-- Show tick if landing_page_uri is not null -->
                        @if ($providerData && $providerData->url)
                            <i class="fa-solid fa-check text-check fs-3"></i>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-0">
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Display Modal if First Login -->
@if ($firstLogin)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var showAlertModal = new bootstrap.Modal(document.getElementById("showAlertModal"), {});
        showAlertModal.show();
    });
</script>
@endif
   
    <div class="d-flex flex-column justify-content-center align-items-center ">

    <div class="d-flex flex-row row m-2 mb-0 w-100 justify-content-center align-items-center">


    <div class="tableFixHead p-0 mt-1 price-table">
        <div class="">
            <table class="table table-bordered m-0 w-full text-center pricing-table ">
                <thead class="border-bottom  shadow">
                    <tr>
                        <th class=" align-middle fixed-column">
                            <h2 class="fw-bold ">Plan Features</h2>
                        </th>
                            

                        @php
    
    $subscribedPlan = $subscriptions->first();
    $subscribedPlanPrice = $subscribedPlan->plan->plan_price ?? 0; 
    $nextBillingDate = $subscribedPlan->next_billing_at ?? null; 
@endphp

@foreach ($plans as $plan)
    <th class="align-middle position-relative">
        <div>
        @php
                
                $isSubscribed = $subscriptions->contains('plan_id', $plan->plan_id);

                
                $isAddonSubscribed = $subscriptions->contains(function ($subscription) use ($plan) {
                    return $subscription->addon == 1 && $subscription->plan_id == $plan->plan_id;
                });
            @endphp
        <h5 class="text-dark mb-2">
                <strong>
                    <span class="text-primary">{{ $plan->plan_name }}</span>&nbsp;<span>{{ $plan->plan_code }}</span>
                </strong>
            </h5>
            <h5 class="text-dark mb-2">
                <strong>
                    ${{ $plan->plan_price }}
                    @if ($isAddonSubscribed)
                        + ${{ $plan->addon_price }}
                    @endif
                </strong>
            </h5>

            @php
                // Check if subscriptions exist and if the user is subscribed to the current plan
                $isSubscribed = $subscriptions->contains('plan_id', $plan->plan_id);
            @endphp
            @php
    // Check if the user has subscribed to the add-on
    $isAddonSubscribed = $subscriptions->contains(function ($subscription) {
        return $subscription->addon == 1; // Assuming 'addon' column is used to track the add-on subscription
    });
@endphp

@if ($isSubscribed)
    <span class="position-absolute top-0 start-50 rounded-1 border border-2 fs-6 translate-middle badge text-primary bg-white">Current Plan</span>
    
    @if ($isAddonSubscribed)
    <p class="mt-1 fw-normal p-1"><small>You have also Subscribed to: <span>{{$plan->addon_code}}</span> for the current month</small></p>
    @else
    <form action="{{ route('addon.preview') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="plan_code" value="{{ $plan->plan_code }}">
    <button type="submit" class="btn btn-primary">Monthly Click Add-On</button>
</form>  
  @endif
    
    @if($nextBillingDate)
        <p class="mt-2 mb-2"><small>Next Renewal Date: {{ \Carbon\Carbon::parse($nextBillingDate)->format('d-M-Y') }}</small></p>
    @endif
            @else
                @if ($plan->plan_price < $subscribedPlanPrice)
                   
                    <span class="text-muted"></span> 
                @else
                    @if ($subscriptions->isNotEmpty())
                      
                    <form action="{{ route('upgrade.preview') }}" method="POST" style="display: inline;">
                    @csrf
                     <input type="hidden" name="plan_code" value="{{ $plan->plan_code }}">
                    <button type="submit" class="btn btn-primary">Upgrade</button>
                  </form>
                    @else
                    <form action="{{ route('subscribe.preview') }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="plan_code" value="{{ $plan->plan_code }}">
    <button type="submit" class="btn btn-primary" id="subscribeButton">Subscribe</button>
</form>
                    @endif
                 
                @endif
            @endif
        </div>
    </th>
@endforeach

                        <th class=" align-middle position-relative">
                        <div>
                        <h5 class="mb-2"><strong><span class="text-primary">Custom</span><span> Enterprise</span></strong></h5>
                        <a data-bs-toggle="modal" data-bs-target="#contactModal" id="save" class="btn btn-primary">Contact Us</a>
                    </div>
                     
                        </th>
                    </tr>
                </thead>
                <tbody>
                <tr>
            
            <td class="fixed-column align-left fs-5 fw-bold">
                <div>
                    <ul>
                        <li>Update Logo</li>
                        <li>Custom URL</li>
                        <li>Zip code availability updates</li>
                        <li>Data updates (speeds, connection types)</li>
                        <li>Self-service portal access</li>
                        <li>Account management support</li>
                        <li>Reporting</li>
                        <li>Maximum Allowed Clicks</li>
                        <li>Maximum Click Monthly Add-On</li>
                    </ul>
                </div>
            </td>
            
            @foreach ($plans as $plan)
            <td>
                <div>
                    <ul>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-xmark text-cross fs-3"></i></li>
                        <li>Monthly</li>
                        <li>up to {{ $plan->max_clicks }}/month</li>
                        <li>${{ $plan->click_addon_price }} for {{ $plan->addon_clicks }} Clicks</li>
                    </ul>
                </div>
            </td>
            @endforeach
           
            <td>
                <div>
                    <ul>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li><i class="fa-solid fa-check text-check fs-3"></i></li>
                        <li>Daily</li>
                        <li>Over 2,000/month</li>
                    </ul>
                </div>
            </td>
        </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

</div>
 <!-- Modal -->
 <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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
                    <textarea class="w-100 p-3 pe-4 border-0 rounded" name="message" rows="5">I am interested in learning more about the Enterprise plan. Please contact me with more information.</textarea>
                        <input type="submit" class="btn btn-primary popup-element " value="Send">
                      
                    </form>
                </div>
                <div class="modal-footer border-0"></div>
            </div>
        </div>
    </div>
    
 <!-- FAQ Section -->
<div class="tableTerms p-0 border-0 mb-5 margin-default">
    <p class="fw-bold ms-3">
    <h2 class="fw-bold ms-2">FAQ's</h2>
    </p>
<div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
      Billing &amp; Payments
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      <ul id="billing" class=" ">
                        <li class="billing">
                            All billing & payments will be upfront in US Dollars
                        </li>
                        <li class="billing">
                            Plans are billed on a calendar billing schedule
                        </li>
                        <li class="billing">
                            Associated Credit Card will be charged on renewal of a plan subscription on the first of each month
                        </li>
                        <li class="billing">
                            Plan price and subscription will be pro-rata or full as applicable on some upgrades
                        </li>
                        <li class="billing">
                            Any credits which arise in the process of upgrades will be kept for adjusting subsequent subscription renewals and will not be refunded
                        </li>
                        <li class="billing">If a subscription renewal auto-debit of the card fails, you will be notified to pay by email (invoice link) or pay through the payment portal</li>
                        <li class="billing">If the maximum number of clicks is reached prior to the end of the month, the plan can be upgraded or a Monthly Add-On can be purchased. </li>
                        <li class="billing">Only one Monthly Add-On can be purchased per month </li>
                        <li class="billing">Clearlink does not offer refunds on a paid subscription or credits if any, but will consider specific cases of erroneous and duplicate payment transactions after review</li>
                        <li class="billing">Clearlink reserves the right to change the product, plan, subscription, limits, name and price, and will send notification of changes</li>

                    </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
      Upgrade / Downgrade / Cancel
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      <ul id="upgrade" class="">
                        <li class="billing">You can upgrade a paid plan at any time, the upgrade will be applied immediately on successful payment</li>
                        <li class="billing">Plan downgrade requests can be sent through a support ticket and will be done only at the end of the current subscription period</li>
                        <li class="billing">Subscription cancellation requests can be sent through a support ticket</li>
                        <li class="billing">Unpaid subscriptions will be automatically cancelled after 15 days </li>
                        <li class="billing">Plan Features & Limits are subject to change based on upgrade / downgrade process</li>
                    </ul>
        
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
      Security / Privacy
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">
      <ul id="security" class="">
                        <li class="billing">Clearlink uses Zoho to manage your subscriptions / support and uses Stripe as a secured payment gateway</li>
                        <li class="billing">Clearlink respects and protects your data as per the <a target="_blank" href="https://www.clearlink.com/privacy/">Privacy Policy</a></li>
                        <li class="billing">Clearlink will send transactional and relevant product promotions and collateral by email to enrich your experience</li>
                    </ul>
      </div>
    </div>
  </div>
</div>
   
<script>
    // Function to update the plan code in the hidden input field
    function updatePlanCode(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const planCode = selectedOption.getAttribute('data-plan-code');
        document.getElementById('hiddenPlanCode').value = planCode || '';
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Select all subscribe buttons
        var subscribeButtons = document.querySelectorAll("button#subscribeButton");

        // Function to check all required conditions
        function checkConditions() {
            const logoUploaded = {{ $companyInfo && $companyInfo->logo_image ? 'true' : 'false' }};
            const companyNameSet = {{ $companyInfo && $companyInfo->company_name ? 'true' : 'false' }};
            const landingPageSet = {{ $companyInfo && $companyInfo->landing_page_uri ? 'true' : 'false' }};
            const providerDataUploaded = {{ $providerData && $providerData->url ? 'true' : 'false' }};
            const firstLogin = {{ $firstLogin ? 'true' : 'false' }};

            // All conditions must be true to proceed
            return (
                logoUploaded &&
                companyNameSet &&
                landingPageSet &&
                providerDataUploaded &&
                !firstLogin
            );
        }

        // Attach event listeners to subscribe buttons
        subscribeButtons.forEach((button) => {
            button.addEventListener("click", function (event) {
                if (!checkConditions()) {
                    event.preventDefault(); // Prevent default action (form submission)
                    var showAlertModal = new bootstrap.Modal(
                        document.getElementById("showAlertModal"),
                        {}
                    );
                    showAlertModal.show(); // Show the modal to alert the user
                }
            });
        });

        // Function to recheck conditions when modal is hidden (optional)
        const modal = document.getElementById("showAlertModal");
        if (modal) {
            modal.addEventListener("hidden.bs.modal", function () {
                checkConditions(); // Recheck conditions when the modal is closed
            });
        }
    });
</script>

@endsection


