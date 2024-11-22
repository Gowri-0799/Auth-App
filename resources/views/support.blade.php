@extends("layouts.admin")
@section('title', "Support Tickets")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">

<div class="d-flex justify-content-center align-items-center">
   {{-- Alert Messages --}}
    <div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 999; width: 300px;">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> 
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0" style="font-size: 30px;">Support Tickets</h2>
    <!-- Add modal trigger attributes to the button -->
    <button id="createTicketBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketForm">
        Create New Ticket
    </button>
</div>
            <div class="card-body p-3">
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('customer.support') }}">
                    <div class="col-md-2">
                        <label for="startDate" class="fw-bold">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="fw-bold">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="fw-bold">Show</label>
                        <select id="showEntries" name="show" class="form-select">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="fw-bold">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                    </div>
                </form>

                <a href="{{ route('show.support') }}" class="btn text-primary text-decoration-underline fw-bold p-0 mb-3">Reset</a>

                @if($supports->count() == 0)
                    
                    <div class="d-flex justify-content-center align-items-center mt-5">
                    <h3>No support tickets found.</h3>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">#</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Date</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Request Type</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Subscription Number</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Company Name</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Message</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supports as $index => $ticket)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ticket->date)->format('d-M-Y') }}</td>
                                        <td>{{ $ticket->request_type }}</td>
                                        <td>{{ $ticket->subscription_number }}</td>
                                        <td>{{ $customer->company_name }}</td>
                                        <td>{{ $ticket->message }}</td>
                                        <td>
                                            @if(strtolower($ticket->status) == 'open')
                                                <span class="badge-success">Open</span>
                                            @else
                                                <span class="badge-fail">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $supports->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create New Ticket Modal -->
<div class="modal fade" id="createTicketForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark bg-popup">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Enter the request message</h1>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body p-0">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <label class="fw-bold">Message*</label>
                    <textarea class="w-100 p-3 pe-4 border-0 rounded" name="message" rows="4" required></textarea>
                    <input type="submit" class="btn btn-primary popup-element mt-3" value="Submit">
                </form>
            </div>
            <div class="modal-footer border-0"></div>
        </div>
    </div>
</div>


<!-- Ensure jQuery is included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Click event to show the create ticket form
        $('#createTicketBtn').click(function(e) {
            e.preventDefault();
            console.log('Create Ticket button clicked'); // Debugging log to ensure the button is working
            $('#overlay').show();  // Show the overlay
            $('#createTicketForm').show();  // Show the modal form
        });

        // Click event to close the create ticket form
        $('#closeFormBtn, #overlay').click(function(e) {
            e.preventDefault();
            console.log('Close button clicked'); // Debugging log to ensure close button works
            $('#overlay').hide();  // Hide the overlay
            $('#createTicketForm').hide();  // Hide the modal form
        });
    });
</script>

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

    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
    }

    .badge-success {
        color: white;
        background-color: #28a745;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .badge-fail {
        color: white;
        background-color: #dc3545;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
@endsection
