@extends("layouts.default")
@section('title', "Customers")
@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">

    <div class="container mt-5">
    <a href="{{ route('customers') }}" class="btn btn-primary position-absolute top-0 end-0 m-3" style="display: none;">
            Sync with zoho
        </a>
    <div class="position-relative">
   
        <a href="{{ route('cust.display') }}" class="btn btn-primary position-absolute top-0 end-0 m-3">
            Invite Partner
        </a>
        <h2 class="text-center mb-4">Partner Details</h2>
        @if ($customers->isEmpty())
            <div class="alert alert-info text-center">
                No partners available
            </div>
        @else

        <table  class="table table-hover text-center table-bordered" style="background-color: #fff;">
            <thead class="table-light">
                <tr>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">ID</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Partner Name</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">partner Email</th>
                    <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
            @foreach ( $customers as $key => $customer )
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->customer_email }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('customers.edit', $customer->zohocust_id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td> <!-- Edit action -->
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
