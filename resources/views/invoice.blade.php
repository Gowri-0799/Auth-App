@extends("layouts.default")
@section('title', "All Invoices")

@section('content')
<div class="container mt-5">
    <h2>All Invoices</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Invoice Number</th>
                <th>Subscription ID</th>
                <th>Credits Applied</th>
                <th>Discount</th>
                <th>Payment Made</th>
                <th>Payment Method</th>
                <th>Invoice Link</th>
                <th>Zoho Customer ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_id }}</td>
                <td>{{ $invoice->invoice_date->format('Y-m-d H:i:s') }}</td> <!-- Format date as needed -->
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->subscription_id }}</td>
                <td>{{ number_format($invoice->credits_applied, 2) }}</td>
                <td>{{ number_format($invoice->discount, 2) }}</td>
                <td>{{ number_format($invoice->payment_made, 2) }}</td>
                <td>{{ $invoice->payment_method }}</td>
                <td><a href="{{ $invoice->invoice_link }}" target="_blank">View Invoice</a></td>
                <td>{{ $invoice->zoho_cust_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
