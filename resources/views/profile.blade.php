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
            <p class="m-0 ">
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
              <a data-bs-toggle="modal" data-bs-target="#addUserModal" class=" btn btn-primary btn-sm me-3">Invite User</a>
            </div>
            <div class="d-flex justify-content-center align-items-center"> No secondary users found </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Invite User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Invite User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="POST"> 
              @csrf
              <div class="row">
                <!-- Smaller input fields -->
                <div class="col-md-6 mb-3">
                  <label for="first_name" class="form-label">First Name*</label>
                  <input type="text" class="form-control form-control-sm" id="first_name" name="first_name" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="last_name" class="form-label">Last Name*</label>
                  <input type="text" class="form-control form-control-sm" id="last_name" name="last_name" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number*</label>
                <input type="text" class="form-control form-control-sm" id="phone" name="phone" required>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Invite User</button>
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
            <h4 class="mb-3">Payment method</h4> @if($payments && $payments->isNotEmpty()) 
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
