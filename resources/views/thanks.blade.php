@extends('layouts.admin')

@section('title', 'Subscription Status')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Main Box --}}
                <div class="card p-4" style="background-color: #eaf1fc; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                    
                    {{-- Title Section --}}
                    <div class="mb-4 text-start">
                        <h4 class="fw-bold mb-1">Subscription Status: Success</h4>
                        <p>Please find the details below:</p>
                    </div>

                    {{-- Bordered Table Content inside the Main Box --}}
                    <div class="table-responsive">
                        <table class="table mb-0" style="border-collapse: collapse; width: 100%;">
                            
                            {{-- Subscription Details Rows with Enclosed Border --}}
                            <tbody>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Plan Name:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">{{ $plans->plan_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Subscription Number:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">{{ $subscriptions->subscription_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Subscription Amount (US$):</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">${{ number_format($plans->plan_price ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Next Billing Date:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">{{ $subscriptions->next_billing_at ? \Carbon\Carbon::parse($subscriptions->next_billing_at)->format('d-M-Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Invoice Number:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">Invoice Status:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">{{ $invoice->status ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-bold" style="border: 2px solid #ddd; padding: 8px;">View Invoice:</th>
                                    <td style="border: 2px solid #ddd; padding: 8px;">
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
@endsection
