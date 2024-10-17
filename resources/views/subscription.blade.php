@extends("layouts.default")
@section('title', "AllSubscriptions")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">

    <div class="container mt-5">
        <h2 class="mb-4">Subscriptions</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subscription ID</th>
                    <th>Subscription Number</th>
                    <th>Plan ID</th>
                    <th>Invoice ID</th>
                    <th>Payment Method ID</th>
                    <th>Next Billing Date</th>
                    <th>Start Date</th>
                    <th>Zoho Customer ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->subscription_id }}</td>
                        <td>{{ $subscription->subscription_number }}</td>
                        <td>{{ $subscription->plan_id }}</td>
                        <td>{{ $subscription->invoice_id }}</td>
                        <td>{{ $subscription->payment_method_id }}</td>
                        <td>{{ $subscription->next_billing_at }}</td>
                        <td>{{ $subscription->start_date }}</td>
                        <td>{{ $subscription->zoho_cust_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
