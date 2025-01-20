
@extends("layouts.admin")
@section('title', "Credit Notes")

@section('content')
<div id="content" class="container-fluid mt-3" style="box-sizing: border-box; margin-left:250px; width:100%;" >
    <div class="row">
    

        <!-- Main Content -->
        <div class="col-12 col-lg-10">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <h2 class="mb-4 text-center text-lg-start" style="font-size: 28px;">Credit Notes</h2> <!-- Title updated -->
            </div>

            <div class="card-body p-3">
                <!-- Table Filters Section -->
                <form class="row g-3 mb-4" method="GET" action="{{ route('creditnotes.filter') }}"> <!-- Route updated -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="startDate" class="form-label fw-bold" >Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" >
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="endDate" class="form-label fw-bold">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" >
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="showEntries" class="form-label fw-bold" >Show</label>
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
                    <button class="btn btn-primary w-100" type="submit">Submit</button>
                        
                    </div>
                </form>

                <a href="{{ route('customer.credites') }}" class="btn text-primary text-decoration-underline fw-bold p-0 pt-2" style="margin-bottom: 20px;">Reset</a> <!-- Updated Reset route -->

                <!-- Check for credit notes -->
                @if($creditnotes->count() == 0)
                    <div class="d-flex justify-content-center align-items-center mt-5">
                    <h3>No credit notes found.</h3>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive"  style="overflow-x: auto;">
                        <table class="table table-hover text-center table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="background-color:#EEF3FB;">#</th>
                                    <th style="background-color:#EEF3FB;"> Date</th>
                                    <th style="background-color:#EEF3FB;">Credit Note #</th>
                                    <th style="background-color:#EEF3FB;">Company Name</th>
                                    <th style="background-color:#EEF3FB;">Invoice Number</th>
                                    <th style="background-color:#EEF3FB;">Credited Amount (USD)</th>
                                    <th style="background-color:#EEF3FB;">Balance (USD)</th>
                                    <th style="background-color:#EEF3FB;">Status</th>
                                    <th style="background-color:#EEF3FB;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($creditnotes as $index => $creditnote)
                                <tr>
                                    <td>{{ (int)$index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($creditnote->credited_date)->format('d-M-Y') }}</td>
                                    <td>{{ $creditnote->creditnote_number }}</td>
                                    <td>{{ $customers->company_name }}</td>
                                    <td>{{ $creditnote->invoice_number }}</td>
                                    <td>{{ number_format($creditnote->credited_amount, 2) }}</td>
                                    <td>{{ number_format($creditnote->balance, 2) }}</td>
                                    <td class="p-2 status">
                                        @if(strtolower($creditnote->status) == 'credited')
                                        <span class="badge-success">Open</span>
                                        @else
                                        <span class="badge-fail">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                       <a href="{{ route('pdf.download', $creditnote->creditnote_id) }}"  class="btn btn-sm btn-primary">
                                          Download PDF
                                         </a>
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






