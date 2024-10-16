@extends("layouts.admin")
@section('title', "My Subscriptions")

@section('content')
<div class="container"
    style="max-width: 1200px; margin: 0 auto; padding: 50px 0; background-color: #f9f9f9; min-height: 100vh;">
    <div class="d-flex flex-row row m-0 w-100 justify-content-center">
        <div class="col-12 col-md-10 col-lg-3"> {{-- Sidebar column for left content --}}
            <div style="padding: 20px;"> {{-- Sidebar padding can be adjusted --}}
                <h5>Sidebar</h5>
                <!-- <p>Your sidebar content goes here.</p> -->
            </div>
        </div>
        <div class="col-12 col-md-10 col-lg-9"> {{-- Main content area --}}
            <div style="display: flex; width: 100%;">
              
                {{-- Left Card with Subscription Details --}}
                <div style="flex: 1; padding: 20px; background-color: #eaf1fc; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); margin-right: 20px; position: relative;"> {{-- Added position relative to the card --}}

                    {{-- Live Badge Positioned on the Top Right --}}
                    <span class="sub-status p-1 px-3 badge-success mt-3 fs-5"
                          style="display: inline-block; float: right; margin-right: 10px; margin-top: -10px;">
                       <strong>{{ $subscriptions->status }}</strong>
                    </span>

                    <h3 style="font-size: 24px; font-weight: bold; color: #004085; margin-bottom: 15px;">{{$plans->plan_code}}</h3>
                    <span style="display: block; font-size: 16px; color: #555; margin-bottom: 10px;"> {{ $subscriptions->subscription_number}} </span>
                    <span style="font-size: 36px; font-weight: bold; color: #000;">US ${{$plans->plan_price}}</span>

                    <div style="margin-top: 25px; display: flex; justify-content: space-between;">
                        <button class="btn btn-primary" style="padding: 12px 25px; border-radius: 8px; background-color: #007bff;">Monthly Click Add-On</button>
                        <button class="btn btn-primary" style="padding: 12px 25px; border-radius: 8px; background-color: #007bff;">Upgrade</button>
                    </div>

                    {{-- Downgrade and Cancellation Buttons in a single row --}}
                    <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                        <a href="#" style="color: #007bff; text-decoration: underline; font-size: 16px;">Cancellation</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#downgradeModal" style="color: #007bff; text-decoration: underline; font-size: 16px;"> Downgrade</a>
                    </div>

                    <div style="margin-top: 20px;">
                        <span style="font-size: 16px; color: #888;">Next Renewal Date: 01-Nov-2024</span>
                    </div>
                </div>
              
                {{-- Right Card with Payment Details --}}
                <div style="flex: 1; padding: 20px; background-color: #eaf1fc; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; justify-content: flex-start; align-items: center;"> {{-- Adjusted flex properties --}}

                    <div class="credit-card acct" style="text-align: center; width: 100%; display: flex; flex-direction: column; justify-content: flex-end; height: 100%;"> {{-- Updated to use flexbox for positioning --}}
                        <div class="card-details" style="margin-top: auto;"> {{-- Add margin-top: auto to push the card number down --}}
                            <div class="card-number" style="font-size: 18px; margin-bottom: 15px;">XXXX XXXX XXXX 4242</div>
                            <div class="card-expiry">
                                <div class="card-name text-uppercase" style="font-size: 14px;">card</div>
                            </div>
                        </div>
                        <div class="expiry" style="margin-top: 20px;">
                            <p class="ms-2 m-0 d-inline p-0"><small>VALID TILL 03/2034</small></p>
                        </div>
                    </div>

                    {{-- Link to update payment method below the card, centered --}}
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="#" style="color: #0066ff; text-decoration: none; font-size: 16px;">Update Payment Method</a>
                    </div>
                </div>
            </div>

            {{-- FAQ Section --}}
            <div class="mt-5">
                <h2 class="fw-bold">FAQ's</h2>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
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
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
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
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
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
            <br>
            {{-- Modal Section --}}
            <!-- Modal for Downgrade Plan -->
            <div class="modal fade" id="downgradeModal" tabindex="-1" aria-labelledby="downgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downgradeModalLabel">Downgrade Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('downgrade_plan')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="downgradeSelect" class="form-label">Select a plan to downgrade to:</label>
                        <select class="form-select" id="downgradeSelect" name="plan_id" required>
    <option selected disabled value="">Choose a plan...</option>
    @foreach( $downgradePlans as $plan)
        <option value="{{ $plan->plan_id }}">{{ $plan->plan_name }} </option>
    @endforeach
</select>         </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

{{-- Include necessary JS files for Bootstrap modal --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection
