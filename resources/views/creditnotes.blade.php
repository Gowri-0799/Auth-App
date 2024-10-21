
@extends("layouts.admin")
@section('title', "Credit Notes")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <h2 class="mb-5" style="font-size: 30px;">Credit Notes</h2> <!-- Title updated -->
            </div>

            <div class="card-body p-3">
                <!-- Table Filters Section -->
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('creditnotes.filter') }}"> <!-- Route updated -->
                    <div class="col-md-2">
                        <label for="startDate" class=" fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class=" fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class=" fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                        <select id="showEntries" name="show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class=" fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-1">
                    <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
                        <!-- <button type="submit" class="btn btn-primary w-100" style="min-width: 80px; font-family: Arial, sans-serif; font-size: 14px; background-color:#DCE9FE">Submit</button> -->
                    </div>
                </form>

                <a href="{{ route('customer.credites') }}" class="btn text-primary text-decoration-underline fw-bold p-0 pt-2" style="margin-bottom: 20px;">Reset</a> <!-- Updated Reset route -->

                <!-- Check for credit notes -->
                @if($creditnotes->count() == 0)
                    <p class="text-center">No credit notes found.</p>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color:#fff; width: 100%; max-width: 100%;">
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
@endsection






