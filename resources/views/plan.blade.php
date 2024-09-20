@extends("layouts.default")
@section('title', "Plans")
@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Plans</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Plan Price ($)</th>
                </tr>
            </thead>
            <tbody>
            @foreach ( $plans as $key => $plan )
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $plan->plan_name }}</td>
                    <td>{{ $plan->plan_price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
