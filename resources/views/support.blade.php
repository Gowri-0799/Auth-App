@extends("layouts.admin")
@section('title', "Support Tickets")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="font-size: 30px;">Support Tickets</h2>
                <button id="createTicketBtn" class="btn btn-primary">Create New Ticket</button>
            </div>

            <div class="card-body p-3">
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('show.support') }}">
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

                <a href="{{ route('show.support') }}" class="btn text-primary text-decoration-underline fw-bold p-0 mb-3">Reset</a>

                @if($supports->count() == 0)
                    <p class="text-center">No support tickets found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color:#fff; width: 100%; max-width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="background-color:#EEF3FB;">#</th>
                                    <th style="background-color:#EEF3FB;">Date</th>
                                    <th style="background-color:#EEF3FB;">Request Type</th>
                                    <th style="background-color:#EEF3FB;">Subscription Number</th>
                                    <th style="background-color:#EEF3FB;">Company Name</th>
                                    <th style="background-color:#EEF3FB;">Message</th>
                                    <th style="background-color:#EEF3FB;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($supports as $index => $ticket)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($ticket->date)->format('d-M-Y') }}</td>
        <td>{{ $ticket->request_type }}</td>
        <td>{{ $ticket->subscription_number }}</td>
        <td>{{ $customers->company_name }}</td>
        <td>{{ $ticket->message }}</td>
        <td class="p-2 status">
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
<div id="overlay" class="overlay" style="display: none;"></div>
<div id="createTicketForm" class="modal-like-form" style="display: none;">
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Enter the request message</h5>
            <button id="closeFormBtn" class="btn-close"></button>
        </div>
        <div class="card-body">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label fw-bold">Message*</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#createTicketBtn').click(function(e) {
            e.preventDefault();
            $('#overlay').show();
            $('#createTicketForm').fadeIn();
        });

        $('#closeFormBtn, #overlay').click(function(e) {
            e.preventDefault();
            $('#overlay').hide();
            $('#createTicketForm').fadeOut();
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
</style>
@endsection
