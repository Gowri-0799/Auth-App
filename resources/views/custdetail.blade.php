@extends("layouts.default")
@section('title', "Customer Detail")
@section('content')
<div id="content" class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fc; margin-left: 280px; width: calc(100% - 240px);">

    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Partner</h2>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        
        @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <h4>Partner Information</h4>
            <div class="row">
               

                <div class="col-md-4 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control form-control-sm" id="first_name" name="first_name">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control form-control-sm" id="last_name" name="last_name">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-sm" id="email" name="customer_email" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control form-control-sm" id="company_name" name="company_name" required>
                </div>
                <div class="col-md-4 mb-3 position-relative">
    <label for="affiliate-ids" class="form-label">Select Affiliate ID*</label>
    <div class="dropdown-with-icon">
        <select class="select2-multiple form-control" name="affiliate_ids[]" multiple id="affiliate-ids">
            @foreach($affiliates as $affiliate)
                <option value="{{ $affiliate->id }}">
                    {{ $affiliate->isp_affiliate_id }} - {{ $affiliate->domain_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
            </div>

            <div class="row">
                
                <div class="col-md-6">
                    <h4>Billing Address</h4>
                    <div class="row">
  
                        <div class="col-md-12 mb-3">
                            <label for="billing_street" class="form-label">Street</label>
                            <input type="text" class="form-control form-control-sm" id="billing_street" name="billing_street">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="billing_city" class="form-label">City</label>
                            <input type="text" class="form-control form-control-sm" id="billing_city" name="billing_city">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="billing_state" class="form-label">State</label>
                            <input type="text" class="form-control form-control-sm" id="billing_state" name="billing_state">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="billing_country" class="form-label">Country</label>
                            <input type="text" class="form-control form-control-sm" id="billing_country" name="billing_country">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="billing_zip" class="form-label">Zip Code</label>
                            <input type="text" class="form-control form-control-sm" id="billing_zip" name="billing_zip">
                        </div>

                       
                    </div>
                </div>

               
                
            </div>

        
            <button type="submit" class="btn btn-primary mt-4">Add Partner</button>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
       $('#affiliate-ids').chosen({
            width: "100%",
                    no_results_text: "Oops, nothing found!"
        });

    });
</script>


@endsection