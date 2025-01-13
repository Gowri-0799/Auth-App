@extends("layouts.admin")
@section('title', "Usage Reports")
@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:70%;" class="p-3">
   <div class="card shadow-sm border-0 rounded-lg">
      <div class="card-header">
         <!-- Title with margin-bottom -->
         <h2 class="mb-5" style="font-size: 30px;">Usage Reports</h2>
         <!-- Added margin-bottom to create space below the title -->
      </div>
      <!-- Date Filter Form -->
<form method="GET" action="{{ route('usagereports') }}" class="d-flex align-items-center mb-4">
    <!-- Filter Dropdown -->
    <div class="me-2">
            <label for="filter" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Filter</label>
        <select id="filter" name="filter" class="form-select" style="width: 150px;">
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
    <div class="me-2">
        <label for="showBy" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show Data By</label>
        <select id="showBy" name="showBy" class="form-select" style="width: 150px;">
            <option value="month" {{ request('showBy') == 'month' ? 'selected' : '' }}>Month</option>
            <option value="week" {{ request('showBy') == 'week' ? 'selected' : '' }}>Week</option>
            <option value="day" {{ request('showBy') == 'day' ? 'selected' : '' }}>Day</option>
        </select>
    </div>
         <!-- Start Date Field -->
    <div class="me-2">
            <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
            <input type="date" id="startDate" name="startDate" class="form-control" 
            value="{{ request('startDate', \Carbon\Carbon::parse($startDate)->toDateString()) }}" style="width: 130px;" disabled>
         </div>
         <!-- End Date Field -->
    <div class="me-2">
            <label for="endDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
            <input type="date" id="endDate" name="endDate" class="form-control" 
            value="{{ request('endDate', \Carbon\Carbon::parse($endDate)->toDateString()) }}" style="width: 130px;" disabled>
    </div>

    <!-- Submit Button -->
    <div>
        <label class="d-block">&nbsp;</label>
        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>
    </div>
</form>



    <a href="{{ route('usagereports') }}" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a>

    <!-- Display Date Range -->
    <div class="mb-4 text-center">
        <p style="font-family: Arial, sans-serif; font-size: 16px;">
            Date From: 
            <span class="fw-bold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span>
            to 
            <span class="fw-bold">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
         </p>
      </div>
      <!-- Chart -->
      <div style="width:90%; height:80%">
         <canvas id="myChart"></canvas>
      </div>
   </div>
</div>
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
               labels: labels, // x-axis labels (dates)
               datasets: [{
                   label: 'Total Clicks',
                   data: data, // y-axis data (clicks)
                   borderColor: '#00aaff', // line color
                   backgroundColor: 'rgba(0, 170, 255, 0.1)', // area color
                   fill: true, // Fill the area under the line
               }]
           },
           options: {
               responsive: true,
               scales: {
                   x: {
                       beginAtZero: true,
                   },
                   y: {
                       beginAtZero: true,
                   }
               }
           }
       });
   
       // Toggle date fields accessibility based on filter selection
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
@endsection
