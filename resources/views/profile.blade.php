@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Profile Section -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Profile</h5>
                </div>
                <div class="card-body">
                    <!-- Account Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Account Details</h6>
                        <p><strong>{{ $customer->customer_name }}</strong></p>
                        <p><i class="fas fa-envelope me-2"></i>{{ $customer->customer_email }}</p>
                        <p><i class="fas fa-user me-2"></i>{{ $customer->first_name }} {{ $customer->last_name }}</p>
                    </div>

                    <!-- Address Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Address</h6>
                        <p>
                            <i class="fas fa-map-marker-alt me-2"></i>{{ $customer->billing_street }}, 
                            {{ $customer->billing_city }}, {{ $customer->billing_state }},
                            {{ $customer->billing_country }} - {{ $customer->billing_zip }}
                        </p>
                    </div>

                    <!-- Update Address Button to Open Modal -->
                    <div class="d-flex">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#updateAddressModal">
                            Update Address
                        </button>
                        <a href="#" class="btn btn-secondary">Update Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Update Modal -->
    <div class="modal fade" id="updateAddressModal" tabindex="-1" aria-labelledby="updateAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateAddressModalLabel">Update Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('customers.addupdate', $customer->zohocust_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Billing Street -->
                        <div class="mb-3">
                            <label for="billing_street" class="form-label">Address*</label>
                            <input type="text" class="form-control" id="billing_street" name="billing_street" value="{{ $customer->billing_street }}" required>
                        </div>
                        <!-- Zip Code -->
                        <div class="mb-3">
                            <label for="billing_zip" class="form-label">Zip Code*</label>
                            <input type="text" class="form-control" id="billing_zip" name="billing_zip" value="{{ $customer->billing_zip }}" required>
                        </div>
                        <!-- City -->
                        <div class="mb-3">
                            <label for="billing_city" class="form-label">City*</label>
                            <input type="text" class="form-control" id="billing_city" name="billing_city" value="{{ $customer->billing_city }}" required>
                        </div>
                        <!-- State -->
                        <div class="mb-3">
                            <label for="billing_state" class="form-label">State*</label>
                            <input type="text" class="form-control" id="billing_state" name="billing_state" value="{{ $customer->billing_state }}" required>
                        </div>
                        <!-- Country -->
                        <div class="mb-3">
                            <label for="billing_country" class="form-label">Country*</label>
                            <input type="text" class="form-control" id="billing_country" name="billing_country" value="{{ $customer->billing_country }}" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Payments Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    @if($payments && $payments->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Number</th>
                                <th>Expiry Year</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->type }}</td>
                                <td>**** **** **** {{ substr($payment->payment_method_id, -4) }}</td>
                                <td>{{ $payment->expiry_year }}</td>
                                <td>
                                    <span class="badge {{ $payment->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="/payments/{{$payment->zoho_cust_id}}" class="btn btn-primary btn-sm">Payment Update</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No payment methods found for this customer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
