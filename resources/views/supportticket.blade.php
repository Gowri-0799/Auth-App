@extends("layouts.default")
@section('title', "Support Tickets")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc;">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="font-size: 30px;">Support Tickets</h2>
            </div>
            <div class="card-body p-3">
                <!-- Filter Form -->
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('support.adfilter') }}" style="margin-top: 20px;"> <!-- Added margin-top here -->
                    <div class="col-md-2">
                        <label for="startDate" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                        <select id="showEntries" name="show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>

                <a href="{{ route('Support.Ticket') }}" class="btn text-primary text-decoration-underline fw-bold p-0 mb-3">Reset</a>

                <!-- Display the table or message if no tickets are found -->
                @if($supports->count() == 0)
                 
                    <div class="d-flex justify-content-center align-items-center mt-5">
                <h3>No support tickets found.</h3>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color:#fff; width: 100%; max-width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">ID</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Date</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Request Type</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Subscription Number</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Company Name</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Message</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Status</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Comments</th> <!-- New Comments Column -->
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Action</th> <!-- New Comments Column -->

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supports as $index => $ticket)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ticket->date)->format('d-M-Y') }}</td>
                                        <td>{{ $ticket->request_type }}</td>
                                        <td>{{ $ticket->subscription_number ?? '' }}</td>
                                        <td>{{ $ticket->company_name }}</td>
                                        <td>{{ $ticket->message }}</td>
                                        
                                        <td class="p-2 status">
                                           @if(strtolower($ticket->status) == 'open')
                                               <span class="badge-success">Open</span>
                                           @else
                                                <span class="badge-fail">Closed</span>
                                           @endif
                                        </td>
                                        <td>
    @if(strtolower($ticket->status) == 'completed')
        <span class="text-muted">Unable to Revoke</span>
    @else
        <button 
            type="button" 
            class="btn btn-sm btn-primary add-comment" >
            Revoke
        </button>
    @endif
</td>
<td>   
    @if(strtolower($ticket->status) == 'completed')
        <span class="text-muted">Closed</span>  
    @elseif(strtolower($ticket->request_type) == 'custom support' || strtolower($ticket->request_type) == 'custom enterprise')
        <button 
            type="button" 
            class="btn btn-sm btn-primary add-comment" 
            data-id="{{ $ticket->zoho_cust_id }}" 
            data-bs-toggle="modal" 
            data-bs-target="#commentModal">
           Close
        </button>
    @else
        @php
            // Set the route dynamically based on request_type
            $route = strtolower($ticket->request_type) == 'downgrade' 
                ? route('downgrade.subscription') 
                : (strtolower($ticket->request_type) == 'cancellation' 
                    ? route('support.Subscription') 
                    : '#');
        @endphp
        
        <form action="{{ $route }}" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" name="plan_code" value="{{ $ticket->plan_code ?? '' }}">
            <input type="hidden" name="subscription_number" value="{{ $ticket->subscription_number }}">
            <input type="hidden" name="subscription_id" value="{{ $ticket->subscription_id ?? '' }}">
            <input type="hidden" name="zoho_cust_id" value="{{ $ticket->zoho_cust_id ?? '' }}">
            <input type="hidden" name="customer_name" value="{{ $ticket->customer_name ?? '' }}">
            <input type="hidden" name="customer_email" value="{{ $ticket->customer_email ?? '' }}">
            
            <button type="submit" class="btn btn-primary">Close</button>
        </form>
    @endif
</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--Add Comment-->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark bg-popup">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="commentModalLabel">Add Comment</h1>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{ route('revoke_ticket') }}" method="POST">
                    @csrf
                    <!-- Ensure name matches the controller validation field 'zoho_cust_id' -->
                    <input type="hidden" name="zoho_cust_id" value="{{ $ticket->zoho_cust_id ?? ''}}">
                    <label class="fw-bold">Comment*</label>
                    <textarea class="w-100 p-3 pe-4 border-0 rounded" name="comment" rows="4" required></textarea>
                    <input type="submit" class="btn btn-primary popup-element mt-3" value="Submit">
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    .modal-like-form {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background-color: #fff;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
   
    document.querySelectorAll('.add-comment').forEach(button => {
        button.addEventListener('click', function () {
            const zohoCustId = this.getAttribute('data-id');
            document.getElementById('zoho_cust_id').value = zohoCustId;
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        startDateInput.addEventListener('change', function () {
            const startDate = this.value; 
            if (startDate) {
                endDateInput.min = startDate; 
            }
        });

        const currentStartDate = startDateInput.value;
        if (currentStartDate) {
            endDateInput.min = currentStartDate;
        }
    });
</script>


@endsection
