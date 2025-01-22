@extends("layouts.default")
@section('title', "Affiliates")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc;">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-bottom: 20px;"> <!-- Added margin-bottom -->
                <h2 class="mb-0" style="font-size: 30px;">Affiliates</h2>
                <!-- Add modal trigger attributes to the button -->
                <button id="addAffiliateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAffiliateModal">
                    Add an Affiliate
                </button>
            </div>
            <div class="card-body p-3">
            @if($errors->any())
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        @foreach ($errors->all() as $error)
                            <div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        {{ $error }}
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('affiliates.index') }}">
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

                <a href="{{ route('affiliates.index') }}" class="btn text-primary text-decoration-underline fw-bold p-0 mb-3">Reset</a>

                @if($affiliates->count() == 0)
                    <p class="text-center">No affiliates found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">#</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Affiliate ID</th>
                                    <th style="font-family: Arial, sans-serif; font-size: 16px; background-color: #EEF1F4;">Domain Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($affiliates as $index => $affiliate)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $affiliate->isp_affiliate_id ??'' }}</td>
                                        <td>{{ $affiliate->domain_name ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="mt-4">
                        {{ $affiliates->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Affiliate Modal -->
<div class="modal fade" id="addAffiliateModal" tabindex="-1" aria-labelledby="addAffiliateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark bg-popup">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5" id="addAffiliateModalLabel">Add an Affiliate</h1>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body p-0">
            @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            <form action="{{ route('affiliates.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="affiliateId" class="fw-bold">Affiliate ID*</label>
        <input type="text" id="affiliateId" name="affiliate_id" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="domainName" class="fw-bold">Domain Name*</label>
        <input type="text" id="domainName" name="domain_name" class="form-control" required>
    </div>
    <input type="submit" class="btn btn-primary popup-element mt-3" value="Add Affiliate">
</form>
            </div>
            <div class="modal-footer border-0"></div>
        </div>
    </div>
</div>

<script>
    // Show toast if there are any errors
    document.addEventListener("DOMContentLoaded", function () {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(function (toast) {
            new bootstrap.Toast(toast).show();
        });
    });
</script>

<style>
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

    .card-header {
        margin-bottom: 20px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .toast-container {
        z-index: 1050;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Update the min attribute of the End Date input when Start Date changes
        startDateInput.addEventListener('change', function () {
            const startDate = this.value; // Get selected start date
            if (startDate) {
                endDateInput.min = startDate; // Set the min attribute
            }
        });

        // Ensure the End Date is valid if already selected
        const currentStartDate = startDateInput.value;
        if (currentStartDate) {
            endDateInput.min = currentStartDate;
        }
    });
</script>
@endsection
