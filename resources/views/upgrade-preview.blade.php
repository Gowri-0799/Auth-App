@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:100%" class="p-3">
    <div class="row">
        <div class="col-md-6">
            <a href="/" class="btn text-primary text-decoration-underline mb-3">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="subscribe-card p-4 shadow border-0 border-top border-primary mb-5 bg-clearlink">
        <div class="row">
            <div class="col-md-6 px-2">
                <h6><strong>Information:</strong></h6>
                <ul class="billing">
                    <li class="billing mb-1">
                        We've implemented calendar billing on the 1st of every month. If you subscribe after the 1st of the month, the first upgrade will be prorated. Subsequent renewals will be charged for the full calendar month.
                    </li>
                </ul>
            </div>

            <div class="col-md-6 ps-5 pe-2">
                <h4 class="mb-3">Current Plan Details</h4>
                <p>{{ $subscription->subscription_number ?? 'N/A' }}</p>
                <p>{{$newPlan->plan_name ?? 'N/A' }}</p>
                <p>US ${{ number_format($newPlan->plan_price ?? 0, 2) }}</p>
                <p><small>Next Renewal Date: {{ \Carbon\Carbon::parse( $subscription->next_billing_at)->format('d-M-Y') }}</small></p>

                <h4 class="mb-3">Selected New Plan Details</h4>
                <p><strong>You are upgrading to</strong></p>
                <p>{{ $newPlan->plan_name ?? 'N/A' }}</p>
                <p>US ${{ number_format($newPlan->plan_price ?? 0, 2) }}</p>

                <!-- Change Plan Button Form -->
                <form action="{{ route('upgrade.subscription') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="plan_code" value="{{ $newPlan->plan_code }}">
                    <button type="submit" class="btn btn-primary mt-3">Change Plan</button>
                </form>
            </div>
        
</div>
@endsection
