@extends("layouts.admin")
@section('title', "Usage Reports")
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Clicks Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 250px; width: calc(100% - 220px);">
  <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header">
                <!-- Title with margin-bottom -->
                <h2 class="mb-5" style="font-size: 30px;">Usage Reports</h2> <!-- Added margin-bottom to create space below the title -->
            </div>
    <!-- Date Filter Form -->
    <form method="GET" action="{{ route('usagereports') }}" class="row mb-4 align-items-end">
        <div class="col-md-3">
        <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">Start Date</label>        
            <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}">
        </div>
        <div class="col-md-3">
        <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px; ">End Date</label>  
                  <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}">
        </div>
        <div class="col-md-2">
        <button class="btn button-clearlink text-primary fw-bold" type="submit">Submit</button>      
      </div>
    
    </form>
    <a href="{{ route('usagereports') }}" class="text-decoration-none mb-3 d-inline-block text-primary" style="margin-bottom: 20px;">Reset</a> <!-- Added margin-bottom -->
<!-- Display Date Range -->
<div class="mb-4 text-center">
    <p  style="font-family: Arial, sans-serif; font-size: 16px;">
        Date From: 
        <span class="fw-bold">
            {{ \Carbon\Carbon::parse(request('startDate') ?? $defaultStartDate)->format('d M Y') }}
        </span>
        to 
        <span class="fw-bold">
            {{ \Carbon\Carbon::parse(request('endDate') ?? $defaultEndDate)->format('d M Y') }}
        </span>
    </p>
</div>
    <!-- Chart -->
    <div>
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
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
</script>
@endsection
