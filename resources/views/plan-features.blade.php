@extends('layouts.default')

@section('title', 'Plan View')

@section('content')
<div id="content" class="p-4" style="background-color: #f8f9fc; margin-left: 300px; width: calc(100% - 300px);">
    <div class="container-fluid mt-3">
        <a href="{{ route('plandb') }}" class="btn text-primary text-decoration-underline mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
         style="position: fixed; top: 30px; right: 30px; z-index: 1050; max-width: 300px; padding: 15px; padding-right: 40px; border-radius: 5px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

    </div>
@endif


        <h1 style="font-size: 28px;" class="mb-4">{{ $plan->plan_code }}</h1>

        <div class="card p-4 shadow-sm border-0" style="background-color: #eaf4ff;">
            <div class="row">

                <!-- Left Side: Existing Features -->
                <div class="col-md-6">
    <h4 class="mb-3">Existing Plan Features</h4>

    @if(empty($features) || !is_array($features))
        <p>No existing plan features</p>
    @else
        <ul class="list-unstyled">
            <li style="margin-bottom: 15px;"><strong>Plan Code:</strong> {{ $plan->plan_code }}</li>
            <li style="margin-bottom: 15px;"><strong>Update Logo:</strong> {{ $features['Update Logo'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Custom URL:</strong> {{ $features['Custom URL'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Zip Code Availability Updates:</strong> {{ $features['Zip Code Availability Updates'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Data Updates:</strong> {{ $features['Data Updates'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Self Service Portal Access:</strong> {{ $features['Self Service Portal Access'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Account Management Support:</strong> {{ $features['Account Management Support'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Reporting:</strong> {{ $features['Reporting'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Maximum Allowed Clicks:</strong> {{ $features['Maximum Allowed Clicks'] ?? 'N/A' }}</li>
            <li style="margin-bottom: 15px;"><strong>Maximum Click Monthly Add-on:</strong> {{ $features['Maximum Click Monthly Add-on'] ?? 'N/A' }}</li>
        </ul>
         @endif
     </div>

                <!-- Right Side: Update Plan Features -->
                <div class="col-md-6">
                    <h4 class="mb-3">Update Plan Features</h4>
                    <form action="{{ route('update.features') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_code" value="{{ $plan->plan_code }}">

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="updateLogo" name="update_logo" 
                                {{ isset($features['Update Logo']) && $features['Update Logo'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="updateLogo">Update Logo</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="customURL" name="custom_url"
                                {{ isset($features['Custom URL']) && $features['Custom URL'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="customURL">Custom URL</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="zipCodeUpdates" name="zip_code_updates"
                                {{ isset($features['Zip Code Availability Updates']) && $features['Zip Code Availability Updates'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="zipCodeUpdates">Zip Code Availability Updates</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="dataUpdates" name="data_updates"
                                {{ isset($features['Data Updates']) && $features['Data Updates'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="dataUpdates">Data Updates</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="selfServiceAccess" name="self_service_access"
                                {{ isset($features['Self Service Portal Access']) && $features['Self Service Portal Access'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="selfServiceAccess">Self Service Portal Access</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="accountSupport" name="account_support"
                                {{ isset($features['Account Management Support']) && $features['Account Management Support'] == 'Yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="accountSupport">Account Management Support</label>
                        </div>
                        <div class="mb-2">
                            <label for="reporting" class="form-label">Reporting:</label>
                            <input type="text" id="reporting" name="reporting" class="form-control"
                                value="{{ $features['Reporting'] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label for="maxClicks" class="form-label">Maximum Allowed Clicks:</label>
                            <input type="text" id="maxClicks" name="max_clicks" class="form-control"
                                value="{{ $features['Maximum Allowed Clicks'] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label for="clickAddon" class="form-label">Maximum Click Monthly Add-on:</label>
                            <input type="text" id="clickAddon" name="click_addon" class="form-control"
                                value="{{ $features['Maximum Click Monthly Add-on'] ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Features</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
