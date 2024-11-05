@extends('layouts.admin')

@section('title', 'Subscription Status')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: #f8fbff;">
                    <div class="card-header">
                        <h4 class="fw-bold">Subscription Status: Success</h4>
                        <p>Please find the details below:</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Plan Name:</th>
                                        <td>{{ $plans->plan_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscription Number:</th>
                                        <td>{{ $subscriptions->subscription_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subscription Amount (US$):</th>
                                        <td>${{ number_format($plans->plan_price ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Next Billing Date:</th>
                                        <td>{{ $subscriptions->next_billing_at ? \Carbon\Carbon::parse($subscriptions->next_billing_at)->format('d-M-Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Invoice Number:</th>
                                        <td>{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Invoice Status:</th>
                                        <td>{{ $invoice->status ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>View Invoice:</th>
                                        <td>
                                            <a href="{{ $invoice->invoice_link ?? '#' }}" class="btn btn-primary btn-sm" target="_blank">View Invoice</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
