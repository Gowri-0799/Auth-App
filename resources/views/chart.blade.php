@extends("layouts.admin")
@section('title', "Usage Reports")
@section('content')

<div id="content" class="container-fluid mt-3" style="box-sizing: border-box;" >
    <div class="row">
        <!-- Main Content -->
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">

            @if ($isPartnerValid )
                <div class="card-header">
                    <h2 class="mb-4 text-center text-lg-start" style="font-size: 28px;">Usage Reports</h2>
                </div>

                <!-- Date Filter Form -->
                <form method="GET" action="{{ route('usagereports') }}" class="row g-2 align-items-end mb-4 px-3">
                    <!-- Filter Dropdown -->
                    <div class="col-12 col-md-2">
                        <label for="filter" class="form-label fw-bold">Filter</label>
                        <select id="filter" name="filter" class="form-select">
                            <option value="month_to_date" {{ request('filter') == 'month_to_date' ? 'selected' : '' }}>Month to Date</option>
                            <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_12_months" {{ request('filter') == 'last_12_months' ? 'selected' : '' }}>Last 12 Months</option>
                            <option value="last_6_months" {{ request('filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="last_3_months" {{ request('filter') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="last_1_month" {{ request('filter') == 'last_1_month' ? 'selected' : '' }}>Last 1 Month</option>
                            <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="last_7_days" {{ request('filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="custom_range" {{ request('filter') == 'custom_range' ? 'selected' : '' }}>Custom Range</option>
                        </select>
                    </div>

                    <!-- Show Data By Dropdown -->
                    <div class="col-12 col-md-2">
                        <label for="showBy" class="form-label fw-bold">Show Data By</label>
                        <select id="showBy" name="showBy" class="form-select">
                            <option value="month" {{ request('showBy') == 'month' ? 'selected' : '' }}>Month</option>
                            <option value="week" {{ request('showBy') == 'week' ? 'selected' : '' }}>Week</option>
                            <option value="day" {{ request('showBy') == 'day' ? 'selected' : '' }}>Day</option>
                        </select>
                    </div>

                    <!-- Start Date Field -->
                    <div class="col-12 col-md-2">
                        <label for="startDate" class="form-label fw-bold">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" 
                            value="{{ request('startDate', \Carbon\Carbon::parse($startDate)->toDateString()) }}" disabled>
                    </div>

                    <!-- End Date Field -->
                    <div class="col-12 col-md-2">
                        <label for="endDate" class="form-label fw-bold">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" 
                            value="{{ request('endDate', \Carbon\Carbon::parse($endDate)->toDateString()) }}" disabled>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 col-md-2">
                        <button class="btn btn-primary fw-bold w-100" type="submit">Submit</button>
                    </div>
                </form>

                <!-- Add "Download CSV" Button --> 
                <div class="col-12 px-3 mb-4 d-flex">
    <a href="{{ route('usagereports.download', ['filter' => request('filter', 'month_to_date'), 'showBy' => request('showBy', 'day')]) }}" 
       class="btn button-clearlink text-primary fw-bold">
       Download CSV
    </a>
</div>

                <!-- Display Date Range -->
                <div class="mb-4 text-center">
                    <p class="fw-bold">
                        Date From: <span class="fw-bold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> 
                        to <span class="fw-bold">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                    </p>
                </div>

                <!-- Chart -->
                <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                    <canvas id="myChart"></canvas>
                </div>

                @else
                    <!-- Show "No Usage Report Found" message -->
                    <div class="text-center" style="height: 100vh; display: flex; justify-content: center; align-items: center;">
                        <p class="fw-bold" style="font-size: 24px;">No Usage report found for the partner.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pass the data from PHP to JavaScript
        const labels = {!! json_encode($dates) !!};
        const data = {!! json_encode($totalClicks) !!};

        // Create the chart using Chart.js
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Clicks',
                    data: data,
                    borderColor: '#00aaff',
                    backgroundColor: 'rgba(0, 170, 255, 0.1)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Toggle date fields based on filter selection
        const filterDropdown = document.getElementById('filter');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        function toggleDateFields() {
            const isCustomRange = filterDropdown.value === 'custom_range';
            startDateInput.disabled = !isCustomRange;
            endDateInput.disabled = !isCustomRange;
        }

        // Run on page load
        toggleDateFields();

        // Add event listener to dropdown
        filterDropdown.addEventListener('change', toggleDateFields);
    });
</script>

<!-- Styling -->
<style>
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 300px;
        }

        .form-label {
            font-size: 12px;
        }

        .form-select, .form-control {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }
</style>
@endsection
