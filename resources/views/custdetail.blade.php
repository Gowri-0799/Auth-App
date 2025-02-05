@extends("layouts.default")
@section('title', "Invite Partners")
@section('content')
<div id="content" class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fc;">
    <div class="container mt-4 p-4" style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h2 class="mb-4 text-start">Invite Partners</h2><br>
        
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <h4 class="mb-3">Company Details</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="company_name" class="form-label fw-bold">Company Name*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="company_name" name="company_name" placeholder="Company name*" required>
                </div>
                <div class="col-md-6">
                    <label for="ein_id" class="form-label fw-bold">EIN ID</label>
                    <input type="text" class="form-control form-control-sm w-75" id="ein_id" name="ein_id" placeholder="EIN ID">
                </div>
                <div class="col-md-6">
    <label for="affiliate_ids" class="form-label fw-bold">Select Affiliate IDs*</label>
    <div class="dropdown-with-icon">
        <select class="form-control chosen-select" name="affiliate_ids[]" multiple id="affiliate-ids">
            @foreach($affiliates as $affiliate)
                <option value="{{ $affiliate->id }}" selected>
                    {{ $affiliate->isp_affiliate_id }} - {{ $affiliate->domain_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>


                <div class="col-md-6">
                    <label for="advertiser_id" class="form-label fw-bold">Advertiser ID</label>
                    <input type="text" class="form-control form-control-sm w-75" id="advertiser_id" name="advertiser_id" placeholder="Advertiser ID">
                </div>
            </div>
            <hr>
            <h4 class="mt-4">Primary Contact Details</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label fw-bold">First Name*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="first_name" name="first_name" placeholder="First name*" required>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label fw-bold">Last Name*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="last_name" name="last_name" placeholder="Last name*" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold">Email*</label>
                    <input type="email" class="form-control form-control-sm w-75" id="email" name="customer_email" placeholder="Email*" required>
                </div>
                <div class="col-md-6">
                    <label for="phone_number" class="form-label fw-bold">Phone Number*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="phone_number" name="phone_number" placeholder="Phone number*" required>
                </div>
            </div>
            <hr>
            <h4 class="mt-4">Company Address Details</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="billing_street" class="form-label fw-bold">Address*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="billing_street" name="billing_street" placeholder="Address*" required>
                </div>
                <div class="col-md-6">
                    <label for="billing_city" class="form-label fw-bold">City*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="billing_city" name="billing_city" placeholder="City*" required>
                </div>
                <div class="col-md-6">
                    <label for="billing_state" class="form-label fw-bold">State*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="billing_state" name="billing_state" placeholder="State*" required>
                </div>
                <div class="col-md-6">
                    <label for="billing_zip" class="form-label fw-bold">Zip Code*</label>
                    <input type="text" class="form-control form-control-sm w-75" id="billing_zip" name="billing_zip" placeholder="Zip code*" required>
                </div>
            </div>
            <hr>
            <h4 class="mt-4">Select Plans</h4>
            <div class="mb-3">
                <label class="form-label fw-bold">Select Plan Type:</label>
                <label class="fw-bold">Select Plan Type:</label>
                <label class="ms-2">
                    <input type="radio" name="planType" value="flat" checked> Flat
                </label>
                <label class="ms-2">
                    <input type="radio" name="planType" value="cpc"> CPC
                </label>
            </div>
            
            <div class="table-responsive" style="max-width: 80%; margin: 0; padding: 0;">
                <table class="table table-hover text-center table-bordered" 
                    style="background-color: #fff; width: 100%; border-radius: 8px; margin-top: 0;">
                    <thead class="table-light">
                        <tr>
                            <th style="background-color:#EEF3FB; width: 5%;">S.No</th>
                            <th style="background-color:#EEF3FB; width: 40%;">Plan Name</th>
                            <th style="background-color:#EEF3FB; width: 25%;">Price</th>
                            <th style="background-color:#EEF3FB; width: 20%;">Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Custom Enterprise</td>
                            <td>Contact Us</td>
                            <td><input type="checkbox" style="transform: scale(1.2);"></td>
                        </tr>
                        @foreach($plans as $index => $plan)
<tr>
    <td>{{ $loop->iteration + 1 }}</td> 
    <td>{{ $plan->plan_name }}</td>
    <td>{{ $plan->plan_price }}</td>
    <td>
        <input type="checkbox" name="plan_codes[]" value="{{ $plan->plan_code }}" style="transform: scale(1.2);">
    </td>
</tr>
@endforeach
                    </tbody>
                </table>
            </div>
            
            <button type="submit" class="btn btn-primary mt-3 px-4 py-2">Invite</button> 
        </form>
    </div>
</div>

<!-- Include jQuery and Chosen.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />

<script>
    $(document).ready(function() {
        $('#affiliate-ids').chosen({
            width: "75%",
            no_results_text: "Oops, nothing found!",
            placeholder_text_multiple: "Select Affiliate IDs"
        });

        // Force close button to show
        setTimeout(function() {
            $('.chosen-choices .search-choice').each(function() {
                if (!$(this).find('.search-choice-close').length) {
                    $(this).append('<span class="search-choice-close"></span>');
                }
            });
        }, 500); // Delay ensures Chosen has rendered
    });
</script>
<style>
hr {
    border: 0;
    height: 1px;
    background: #ccc; /* Light gray line */
    margin: 20px 0;
}
/* Ensure close button appears */
#affiliate-ids + .chosen-container .chosen-choices li.search-choice .search-choice-close {
    position: absolute;
    right: 4px;
    top: 52%;
    transform: translateY(-48%);
    width: 14px;
    height: 14px;
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABGklEQVR42pXTT0gCQRSA4Y8NCJrJxmQzQgoRBxEX4AIOoH8A4gsw4gtIDoCizBAsBAcZQUAsFvsFsnEQo9ixHYEu9XMvyWdmdmPpW95VQHEZzjbC6K09wzAXjbEbkMsuAPXvblmPntvHGK8kONqTYPbnAOXwE5d5idwPdmZLE3Q84cuw7uAWPcmWnCfWEQWBfbByhUQ6r+RU5lIykGm2Si1ZW4U1baxATdiyJHKTEy5YZ64R0AMVWlfrBl2g65qyw0bUqM3GbvRAwbbz1n9plGoNgsxZmMa/dG1ClO80h/fuDVRL64A+PMIhNOcDPD/Htb63JPgPnzI59B+FgugLzmj/P+w8SR4/wZPZ4RCNMXUpgAAAABJRU5ErkJggg==");
    background-size: contain;
    background-repeat: no-repeat;
    cursor: pointer;
    opacity: 1; /* Ensure it's fully visible */
}

/* Ensure tag has enough space for close button */
#affiliate-ids + .chosen-container .chosen-choices li.search-choice {
    padding-right: 24px !important;
}


 </style>

@endsection
