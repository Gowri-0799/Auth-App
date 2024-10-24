@extends('layouts.admin')

@section('title', 'Thank You')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center" style="background-color: #f8fbff;">
                    <div class="card-header">
                        <h4 class="fw-bold">Selected New Plan Details</h4>
                    </div>
                    <div class="card-body">
                        <p>You are Upgraded to</p>
                        <h5>{{ $plans->plan_name ?? 'N/A' }}</h5>
                        <h6>Subscription Number: {{ $subscriptions->subscription_number ?? 'N/A' }}</h6>
                        <p>US ${{ number_format($plans->plan_price ?? 0, 2) }}</p> 
                        <p>Next Renewal Date: {{ $subscriptions->next_billing_at ? \Carbon\Carbon::parse($subscriptions->next_billing_at)->format('F j, Y') : 'N/A' }}</p>
                        
                        <a href="{{ route('showplan') }}" class="btn btn-primary mt-3">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
