@extends("layouts.default")
@section('title', "Plans")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Title with margin-bottom -->
                <h2 class="mb-5" style="font-size: 30px;">Available Plans</h2>

                <a href="{{ route('plans') }}" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Sync Plans with Zoho Billing</a>
            </div>
            
            <div class="card-body p-3">

                @if($plans->count() == 0)
                  
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('plans') }}" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Add Plan</a>
                    </div>
                @else
               
                    <div class="table-responsive">
                        <div class="col-md-8 mx-auto"> <!-- Centered and limited width -->
                            <table class="table table-hover text-center table-bordered" style="background-color: #fff;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">#</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color: #EEF1F4;">Plan Name</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Plan Price $</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Addon Name</th>
                                        <th style="font-family: Arial, sans-serif; font-size: 16px;background-color:#EEF1F4;">Addon Price $</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ( $plans as $key => $plan )
                                    <tr>
                                     <td>{{ ++$key }}</td>
                                     <td>{{ $plan->plan_name }}</td>
                                     <td>{{ $plan->plan_price }}</td>
                                     <td>{{ $plan->addon_name }}</td>
                                     <td>{{ $plan->addon_price }}</td>
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
