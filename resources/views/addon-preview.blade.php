@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div id="content" style="box-sizing: border-box;" class="p-3">
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('showplan') }}" class="btn text-primary text-decoration-underline mb-3">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="subscribe-card p-4 shadow border-0 border-top border-primary mb-5 bg-clearlink">
        <div class="row">
            <div class="col-md-6 px-2">
                <h6><strong>Information:</strong></h6>
                <ul class="billing">
                    <li class="billing mb-1">
                    The total amount for the Monthly Add-On will be charged once you complete the purchase process.
                     </li>
                </ul>
            </div>

            <div class="col-md-6 ps-5 pe-2">
                <h4 class="mb-3">Current Plan Details</h4>
                <p>{{ $subscription->subscription_number ?? 'N/A' }}</p>
                <p>{{ $plan->plan_name ?? 'N/A' }}</p>
                <p>US ${{ number_format($plan->plan_price ?? 0, 2) }}</p>
                <p><small>Next Renewal Date: {{ \Carbon\Carbon::parse($subscription->next_billing_at)->format('d-M-Y') }}</small></p>

                <h4 class="mb-3">Add-On Plan Details</h4>
                <p><strong>You are upgrading to</strong></p>
                <p>{{ $newPlan->addon_name ?? 'N/A' }}</p>
                <p>US ${{ number_format($newPlan->addon_price ?? 0, 2) }}</p>

                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#termsModal">
                    Add Add-On
                </button>
               

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content bg-popup">
            <div class="modal-header bg-popup">
                <h3 class="modal-title fw-bold" id="exampleModalLabel">Terms and Conditions</h3>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body terms-modal">
             
                <p>Any plans or packages selected herein by you (“Deliverables”) shall be subject to these Terms and Conditions between you (the “<b> Advertiser</b>”), and Clear Link Technologies, LLC (the “<b>Media Company</b>”) and are governed by the AAAA/IAB Standard Terms and Conditions for Internet Advertising for Media Buys One Year or Less version 3.0 available at www.iab.com (the “<b>IAB Terms</b>”), with Utah as governing law and venue. Capitalized terms not defined herein will have the meaning as set forth in the IAB Terms. Additionally, the following terms apply and, in any case of conflict with the IAB Terms, will control:</p>
    <ol class="list-group list-group-numbered bg-popup ">
        <li class="list-group-item border-0 bg-popup">The Advertiser will be considered both the <b>“Agency”</b> and <b>“Advertiser”</b> under the IAB Terms. <b>“Clearlink”</b> is the “Media Company”, as defined in the IAB Terms. Any modification to these Terms and Conditions must be in writing signed by both parties.</li>
        <li class="list-group-item border-0 bg-popup">Advertiser will prepay Clearlink in advance of Clearlink providing the Deliverables.</li>
        <li class="list-group-item border-0 bg-popup">
            <u>The Term</u> of the Deliverables shall commence and conclude in accordance with the Deliverable term selected by Advertiser. The Deliverables under these Terms and Conditions are non-exclusive.
        </li>
        <li class="list-group-item border-0 bg-popup"><u>Deliverables</u> include services performed on the following basses: (i) flat-fee; (ii) cost per phone call; (iii) cost per view; (iv) cost per thousand impressions; (v) cost per lead; (vi) cost per click; (vii) cost per install of an application; and (vii) cost per acquisition. </li>
        <li class="list-group-item border-0 bg-popup"><u>Editorial Control and Advertiser Responsibilities</u> Clearlink will, upon reasonable written request of Advertiser, provide content for review prior to publication. Notwithstanding anything in the IAB Terms to the contrary, Clearlink retains full and absolute creative and editorial control over all content. Clearlink does not ensure that competitor advertising/offers are not visible on results or plan pages following a search being conducted on Advertiser or its products. Clearlink will not list plan/product information for new plans or products prior to launch date. Advertiser is responsible for customer sign-up and all customer support and administration.</li>
        <li class="list-group-item border-0 bg-popup"><u>Tracking</u> Clearlink may place tracking pixels or server pixels tracking on content, websites, mobile applications, and Advertiser’s tracking platform to track any campaign and Deliverables. Agency will provide a tracking platform for the campaign, which will monitor and track and report on campaign performance and Deliverables and be acceptable to Clearlink in its reasonable discretion. Advertiser will not modify or restrict access without Clearlink’s prior written consent. Additionally, Advertiser shall implement conversion tracking via server-to-server (S2S) postback integration using Media Company’s TUNE tracking platform. </li>
        <li class="list-group-item border-0 bg-popup"><u>Intellectual Property</u> Clearlink owns and retains rights to all intellectual property related to (i) the content, products, and services it and its vendors provide under the Deliverables (the “Clearlink Products”), and (ii) all data used by Clearlink to provide the Clearlink Products. Advertiser retains right to its intellectual property, including Advertiser-generated advertising materials and Advertiser’s products, services, and business. All materials not provided by Advertiser that are used by Clearlink under these Terms and Conditions will be the exclusive property of Clearlink.</li>
        <li class="list-group-item border-0 bg-popup">Section II ‘AD PLACEMENT AND POSITIONING’ is hereby amended to add the following:<ol>
                <li> e) <u>Host Websites</u> Clearlink will list information regarding product comparisons of Selected Products: (i) Clearlink-owned website(s); (ii) third-party websites, which will feature the brand of the relevant third party (whether or not credited as being powered by or otherwise provided by Clearlink); and (iii) co-branded websites. </li>
            </ol>
        </li>
        <li class="list-group-item border-0 bg-popup">Section IX ‘Ad Materials’ is hereby amended to add the following:
            <ol>
                <li>
                    h)<u> Selected Products</u>
                    <ol class="list-group list-group-numbered bg-popup">
                        <li class="list-group-item border-0 bg-popup"><b>“Selected Products”</b> means Advertiser’s products for which Clearlink obtains sufficient Product Information to feature on the Host Websites. </li>
                        <li class="list-group-item border-0 bg-popup">Clearlink reserves the right to exclude or remove any of Advertiser’s products from the Host Website at any time if Clearlink determines in its sole discretion: (i) the product category is not relevant to Clearlink’s online product comparison service; (ii) the products are not in the best interest of consumers; (iii) it does not have sufficient Product Information to list the products on the Host Website; (iv) there is excessive negative customer feedback and/or press coverage in respect of the relevant products; (v) Advertiser or Agency is using misleading or illegal advertising practices, such as bait and switch tactics; or (vi) the Product Information does not comply with the warranties set forth in Section IX(i)(2). </li>
                    </ol>
                </li>
                <li>
                    i) <u>Product Information</u>
                    <ol class="list-group list-group-numbered bg-popup">
                        <li class="list-group-item border-0 bg-popup">Clearlink will compile the information it displays on the Host Websites regarding the Selected Products using: (i) information supplied by Advertiser; and (ii) information which Clearlink obtains from Advertiser’s website, such information being referred to as the “<b>Product Information</b>”. </li>
                        <li class="list-group-item border-0 bg-popup">Advertiser warrants that all the Product Information relating to each Selected Product is correct and accurate, is not misleading or deceptive, and complies with all applicable legal, statutory, regulatory, and other requirements and standards. </li>
                        <li class="list-group-item border-0 bg-popup">Advertiser must notify Clearlink if: (i) any of the Selected Products are subject to a recall notice or manufacturer’s warning; or (ii) any of the Product Information provided to Clearlink is incorrect or violates section IX(i)(2). </li>
                        <li class="list-group-item border-0 bg-popup">Clearlink will update the information it displays on the Host Website as soon as reasonably possible after receiving notification from Advertiser in accordance with this Section.</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
</div>            
            
            <div class="modal-footer d-flex flex-row justify-content-end bg-popup">
                <div>
                    <input class="me-2 fs-5 form-check-input-lg" type="checkbox" id="termsCheckbox" />
                    <span><strong>I agree to these Terms and Conditions</strong></span>
                </div>
                <button id="submitAddonButton" class="btn btn-primary disabled">Submit</button>
            </div>
        </div>
    </div>
</div>

<form id="addonForm" action="{{ route('addon') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $newPlan->plan_id }}">
                    <input type="hidden" name="zoho_cust_id" value="{{ $subscription->zoho_cust_id ?? 'default_customer_id' }}">
    <input type="hidden" name="subscription_number" value="{{ $subscription->subscription_number ?? 'default_subscription_number' }}">
    <input type="hidden" name="plan_name" value="{{ $plan->plan_name ?? 'default_plan_name' }}">
    <input type="hidden" name="amount" value="{{ $plan->plan_price ?? 0 }}">
    <input type="hidden" name="consent" id="consent" value="0">
                </form>
           

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('termsCheckbox');
        const submitButton = document.getElementById('submitAddonButton');
        const form = document.getElementById('addonForm');

        
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                submitButton.classList.remove('disabled');
            } else {
                submitButton.classList.add('disabled');
            }
        });

        
        submitButton.addEventListener('click', function () {
            if (checkbox.checked) {
                form.submit();
            }
        });
    });
</script>
@endsection
