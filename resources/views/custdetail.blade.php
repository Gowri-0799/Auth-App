@extends("layouts.default")
@section('title', "Customer Detail")
@section('content')
<div id="content" class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fc; margin-left: 280px; width: calc(100% - 240px);">

    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Customer</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form starts here -->
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <!-- Customer Basic Info Section -->
            <h4>Customer Information</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="display_name" class="form-label">Display Name</label>
                    <input type="text" class="form-control form-control-sm" id="display_name" name="customer_name" required>
                </div>

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
                
            </div>

            <!-- Billing and Shipping Address Section -->
            <div class="row">
                <!-- Shipping Address on the Left -->
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

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary mt-4">Add Customer</button>
        </form>
    </div>
</div>
@endsection
