@extends('layouts.admin') 
@section('title', 'Dashboard') 
@section('content') 

<div id="content" style="box-sizing: border-box; margin-left: 300px; width: auto; max-width: 1200px;" class="p-3">
  <div class="inner">
    <div class="mb-4 w-100">
      <h2 class="mt-2">Profile</h2>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="card w-100 border-0 bg-clearlink rounded mb-3">
          <div class="card-body">
            <h4 class="right-margin">Account Details</h4>
            <p class="m-0">
              <i class="fa fa-building right-margin text-primary" aria-hidden="true"></i>
              <strong></strong>
            </p>
            <p class="m-0">
              <i class="fa fa-user right-margin text-primary" aria-hidden="true"></i>
              <strong>{{ $customer->customer_name }}</strong>
            </p>
            <p class="m-0">
              <i class="fa fa-envelope right-margin text-primary" aria-hidden="true"></i>{{ $customer->customer_email }}
            </p>
            <p class="m-0">
              <i class="fa-solid fa-phone right-margin text-primary"></i>
            </p>
            <div class="d-flex flex-row mb-3">
              <div class="m-0">
                <i class="fa fa-address-card right-margin text-primary" aria-hidden="true"></i>
              </div>
              <div>{{ $customer->billing_street }}, <br>{{ $customer->billing_city }}, <br>{{ $customer->billing_state }}, <br>{{ $customer->billing_country }}
                <br>{{ $customer->billing_zip }}
              </div>
            </div>
            <div class="d-flex flex-row">
              <a class="btn btn-primary  right-margin rounded" data-bs-toggle="modal" data-bs-target="#updateAddressModal">Update Address</a>
              <a class="btn text-primary text-decoration-underline fw-bold ps-0" data-bs-toggle="modal">Update Password</a>
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
            <div class="d-flex justify-content-center align-items-center"> No secondary users found </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Invite User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;"> <!-- Modal width adjusted -->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Invite User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Form with action, hidden token, and updated fields -->
            <form action="/invite-user" method="POST">
              @csrf
              <input type="hidden" name="zoho_cust_id" value="4631236000001671132">
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
              <div class="modal-footer">
                <input type="submit" class="btn btn-primary text-white w-100 px-3 py-2 rounded" value="Save Changes"> <!-- Full width button -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Payment Method Section -->
    <div class="row mb-5">
      <div class="col-lg-6">
        <div class="card w-100 border-0 bg-clearlink ">
          <div class="card-body table-responsive right-margin">
            <h4 class="mb-3">Payment method</h4> 
            @if($payments && $payments->isNotEmpty()) 
            <table class="table">
              <tbody>
                <tr>
                  <td colspan="2" style="padding-bottom:20px;">Type</td>
                  <td>Number</td>
                  <td>Expiry Date</td>
                  <td>Status</td>
                  <td>Action</td>
                </tr> 
                @foreach($payments as $payment) 
                <tr>
                  <td class="pt-4">
                    <i class="fa fa-credit-card"></i>
                  </td>
                  <td class="pt-4">
                    {{ $payment->type }}
                  </td>
                  <td class="pt-4"> ** ** ** {{ substr($payment->payment_method_id, -4) }}
                  </td>
                  <td class="pt-4">
                    {{ $payment->expiry_month }}/{{ $payment->expiry_year }}
                  </td>
                  <td class="status pt-4">
                  @if(strtolower($payment->status) == 'paid')
                      <span class="badge-success">Active</span>
                   @else
                       <span class="badge-fail">Pending</span>
                  @endif   
                  </td>
                  <td class="pt-4">
                    <div class="col-lg mb-2">
                      <a href="/payments/{{$payment->zoho_cust_id}}" class="btn btn-primary btn-sm ">Update</a>
                    </div>
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
</div> 
@endsection
