@extends('layouts.admin')

@section('title', 'Subscribe Plan Preview')

@section('content')
<div id="content" style="box-sizing: border-box; margin-left: 300px; max-width: 1200px; width: 100%;" class="p-3">
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('showplan') }}" class="btn text-primary text-decoration-underline mb-3">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="subscribe-card p-4 shadow-sm border-0 rounded bg-light">
        <div class="row">
            <!-- Information Section -->
            <div class="col-md-6 px-2 border-end">
                <h6><strong>Information:</strong></h6>
                <ul class="list-unstyled">
                    <li>
                        We've implemented calendar billing on the 1st of every month. If you subscribe after the 1st of the month, the first month will be prorated. Subsequent renewals will be charged for the full calendar month.
                    </li>
                </ul>
            </div>

            <!-- Plan Details Section -->
            <div class="col-md-6 ps-4">
                <h4 class="mb-3"><strong>Plan Details</strong></h4>
                <p class="mb-3"><strong>You are subscribing to</strong></p>
                <p><strong>{{ $newPlan->plan_name ?? 'N/A' }}</strong></p>
                <p>US ${{ number_format($newPlan->plan_price ?? 0, 2) }}</p>

                <!-- Subscribe Button -->
                <a href="{{ route('subscribe', $newPlan->plan_code) }}" class="btn btn-primary mt-3">Subscribe</a>
            </div>
        </div>
    </div>
</div>
@endsection
