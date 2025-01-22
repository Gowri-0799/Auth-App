@extends("layouts.default")
@section('title', "Addons")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc;">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Title with margin-bottom -->
                <h2 class="mb-5" style="font-size: 30px;">Available Addons</h2>

                <!-- Button in the top-right corner -->
                <a href="{{ route('addons') }}" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Sync Addons with Zoho Billing</a>
            </div>
            
            <div class="card-body p-3">
              
                <!-- Check for addons -->
                @if($addons->count() == 0)
                    <!-- Centered Add Addon Button -->
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('addons') }}" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Add Addon</a>
                    </div>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <div class="col-md-8 mx-auto"> <!-- Centered and limited width -->
                            <table class="table table-hover text-center table-bordered" style="background-color: #fff;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">#</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Addon Code</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Name</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Quantity</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Price $</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Unit Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($addons as $key => $addon)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $addon->addon_code }}</td>
                                        <td>{{ $addon->name }}</td>
                                        <td>{{ $addon->quantity }}</td>
                                        <td>{{ $addon->price }}</td>
                                        <td>{{ $addon->unit_name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
