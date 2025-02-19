@extends("layouts.admin")
@section('title', "Support Tickets")

@section('content')
<div id="content" class="container-fluid mt-3" style="box-sizing: border-box; min-height: 100vh; padding-bottom: 20px;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12">
            <!-- Alert Messages -->
            <div id="alert-container">
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

            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0" style="font-size: 24px;">Support Tickets</h2>
                    <button id="createTicketBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketForm">
                        Create New Ticket
                    </button>
                </div>
                <div class="card-body p-3">
                    <!-- Filters -->
                    <form class="row gy-3 gx-3 mb-4" method="GET" action="{{ route('customer.support') }}">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="startDate" class="fw-bold">Start Date</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="endDate" class="fw-bold">End Date</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="showEntries" class="fw-bold">Show</label>
                            <select id="showEntries" name="show" class="form-select">
                                <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="search" class="fw-bold">Search</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-1 d-flex align-items-end">
                            <button class="btn button-clearlink text-primary fw-bold w-100" type="submit">Submit</button>
                        </div>
                    </form>

                    <!-- Reset Link -->
                    <a href="{{ route('show.support') }}" class="btn text-primary text-decoration-underline fw-bold p-0 mb-3">Reset</a>

                    <!-- Table -->
                    @if($supports->count() == 0)
                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <h3>No support tickets found.</h3>
                        </div>
                    @else
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Request Type</th>
                                        <th>Subscription Number</th>
                                        <th>Company Name</th>
                                        <th>Message</th>
                                        <th>Status</th>
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

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $supports->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        startDateInput.addEventListener('change', function () {
            const startDate = this.value;
            if (startDate) {
                endDateInput.min = startDate;
            }
        });

        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
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

    /* .badge-success {
        color: white;
        background-color: #28a745;
        padding: 5px 10px;
        border-radius: 5px;
    } */

    /* .badge-fail {
        color: white;
        background-color: #dc3545;
        padding: 5px 10px;
        border-radius: 5px;
    } */
</style>
@endsection
