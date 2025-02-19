@extends("layouts.admin")
@section('title', "Credit Notes")

@section('content')
<!-- <div id="content" class="container-fluid mt-3" style="box-sizing: border-box; min-height: 100vh; padding-bottom: 20px;"> -->
<div id="content" style="box-sizing: border-box;" class="p-3">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header">
                    <h2 class="mb-4 text-center text-lg-start" style="font-size: 28px;">Credit Notes</h2>
                </div>

                <div class="card-body p-3">
                    <!-- Table Filters Section -->
                    <form class="row gy-3 gx-3 mb-4" method="GET" action="{{ route('creditnotes.filter') }}">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="startDate" class="form-label fw-bold">Start Date</label>
                            <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="endDate" class="form-label fw-bold">End Date</label>
                            <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="showEntries" class="form-label fw-bold">Show</label>
                            <select id="showEntries" name="show" class="form-select">
                                <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="search" class="form-label fw-bold">Search</label>
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-6 col-lg-1 d-flex align-items-end">
                        <button class="btn button-clearlink text-primary fw-bold w-100" type="submit">Submit</button>                        </div>
                    </form>

                    <!-- Reset link -->
                    <a href="{{ route('customer.credites') }}" class="text-primary text-decoration-underline fw-bold mb-3 d-inline-block">Reset</a>

                    <!-- Check for credit notes -->
                    @if($creditnotes->count() == 0)
                        <div class="d-flex justify-content-center align-items-center mt-5">
                            <h3>No credit notes found.</h3>
                        </div>
                    @else
                        <!-- Responsive Table -->
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-hover text-center table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="background-color: #EEF3FB;">#</th>
                                        <th style="background-color: #EEF3FB;">Date</th>
                                        <th style="background-color: #EEF3FB;">Credit Note #</th>
                                        <th style="background-color: #EEF3FB;">Company Name</th>
                                        <th style="background-color: #EEF3FB;">Invoice Number</th>
                                        <th style="background-color: #EEF3FB;">Credited Amount (USD)</th>
                                        <th style="background-color: #EEF3FB;">Balance (USD)</th>
                                        <th style="background-color: #EEF3FB;">Status</th>
                                        <th style="background-color: #EEF3FB;">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($creditnotes as $index => $creditnote)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($creditnote->credited_date)->format('d-M-Y') }}</td>
                                        <td>{{ $creditnote->creditnote_number }}</td>
                                        <td>{{ $customers->company_name }}</td>
                                        <td>{{ $creditnote->invoice_number }}</td>
                                        <td>{{ number_format($creditnote->credited_amount, 2) }}</td>
                                        <td>{{ number_format($creditnote->balance, 2) }}</td>
                                        <td>
                                            @if(strtolower($creditnote->status) == 'credited')
                                                <span class="badge-success">Open</span>
                                            @else
                                                <span class="badge-fail">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pdf.download', $creditnote->creditnote_id) }}" class="btn btn-sm btn-primary">Download PDF</a>
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
@endsection
