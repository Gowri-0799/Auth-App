
@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:100%" class="p-3">
    <!-- Card Wrapper for the Plan Details -->
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
    // Get the price and next billing date of the subscribed plan
    $subscribedPlan = $subscriptions->first();
    $subscribedPlanPrice = $subscribedPlan->plan->plan_price ?? 0; // Assuming 'plan' relation is set up
    $nextBillingDate = $subscribedPlan->next_billing_at ?? null; // Get next_billing_at from subscription
@endphp

@foreach ($plans as $plan)
    <th class="align-middle position-relative">
        <div>
            <h5 class="text-dark mb-2">
                <strong><span class="text-primary">{{ $plan->plan_name }}</span>&nbsp;<span>{{ $plan->plan_code }}</span></strong>
            </h5>
            <h5 class="text-dark mb-2">
                <strong>${{ $plan->plan_price }}</strong>
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
        <a href="{{ route('addon', $plan->plan_code) }}" class="btn btn-primary">Monthly Click Add-On</a>
    @endif
    
    @if($nextBillingDate)
        <p class="mt-2 mb-2"><small>Next Renewal Date: {{ \Carbon\Carbon::parse($nextBillingDate)->format('d-M-Y') }}</small></p>
    @endif
            @else
                @if ($plan->plan_price < $subscribedPlanPrice)
                    <!-- No buttons for plans cheaper than the subscribed plan -->
                    <span class="text-muted"></span> <!-- Optional message for user -->
                @else
                    @if ($subscriptions->isNotEmpty())
                        <!-- Upgrade Button with Form -->
                        <form action="{{ route('upgrade.subscription') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="plan_code" value="{{ $plan->plan_code }}">
                            <button type="submit" class="btn btn-primary">Upgrade</button>
                        </form>
                    @else
                        <a href="{{ route('subscribe', $plan->plan_code) }}" class="btn btn-primary">Subscribe</a>
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
            <!-- Fixed column for plan features -->
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
            <!-- <td>
                <div>
                    <ul>
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
            </td> -->

            <!-- Loop over each plan to display corresponding features -->
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
            <!-- Static "Contact Us" column for the last column -->
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
   
   
   

@endsection


