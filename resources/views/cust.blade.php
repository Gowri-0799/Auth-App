@extends("layouts.default")
@section('title', "Customers")
@section('content')
    <div class="container mt-5">
   
    <div class="position-relative">
    <a href="{{ route('cust.display') }}" class="btn btn-primary position-absolute top-0 end-0 m-3">
    Invite partner
</a>
        <h2 class="text-center mb-4">Customers Details</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                </tr>
            </thead>
            <tbody>
            @foreach ( $customers as $key => $customer )
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $customer->Customer_name }}</td>
                    <td>{{ $customer->Customer_email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
