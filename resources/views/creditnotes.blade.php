
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
                        <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                        <select id="showEntries" name="show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100" style="min-width: 80px; font-family: Arial, sans-serif; font-size: 14px;">Submit</button>
                    </div>
                </form>

                <a href="" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a> <!-- Updated Reset route -->

                <!-- Check for credit notes -->
                @if($creditnotes->count() == 0)
                    <p class="text-center">No credit notes found.</p>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color: #fff; width: 100%; max-width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Credit Note Date</th>
                                    <th>Credit Note Number</th>
                                    <th>Company Name</th>
                                    <th>Invoice Number</th>
                                    <th>Credited Amount (USD)</th>
                                    <th>Balance (USD)</th>
                                    <th>Status</th>
                                    <th>View</th>
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
                                    <td>
                                        @if(strtolower($creditnote->status) == 'open')
                                        <span class="badge bg-success">Open</span>
                                        @else
                                        <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td><a href="" target="_blank" class="btn btn-primary">Download PDF</a></td> <!-- Added PDF view button -->
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






