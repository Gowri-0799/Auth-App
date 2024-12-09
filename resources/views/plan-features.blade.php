@extends('layouts.default')

@section('title', 'Plan View')

@section('content')
<div id="content" class="p-4" style="background-color: #f8f9fc; margin-left: 300px; width: calc(100% - 300px);">
    <div class="container-fluid mt-3">
        <a href="{{ route('plandb') }}" class="btn text-primary text-decoration-underline mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <h1 style="font-size: 28px;" class="mb-4">{{$plan->plan_code }}</h1>

        <div class="card p-4 shadow-sm border-0" style="background-color: #eaf4ff;">
            <div class="row">
            
                <div class="col-md-6">
    <h4 class="mb-3">Existing Plan Features</h4>
    <ul class="list-unstyled">
        <li style="margin-bottom: 15px;"><strong>Plan Code:</strong>{{$plan->plan_code }}</li>
        <li style="margin-bottom: 15px;"><strong>Update Logo:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Custom URL:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Zip Code Availability Updates:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Data Updates:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Self Service Portal Access:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Account Management Support:</strong> Yes</li>
        <li style="margin-bottom: 15px;"><strong>Reporting:</strong> Weekly</li>
        <li style="margin-bottom: 15px;"><strong>Maximum Allowed Clicks:</strong> up to 1,250/month</li>
        <li style="margin-bottom: 15px;"><strong>Maximum Click Monthly Add-on:</strong> $1,500 for 750 clicks (limit of 2,000 total clicks/mo)</li>
    </ul>
</div>

                <!-- Right Side: Update Plan Features -->
                <div class="col-md-6">
                    <h4 class="mb-3">Update Plan Features</h4>
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="updateLogo" name="update_logo" checked>
                            <label class="form-check-label" for="updateLogo">Update Logo</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="customURL" name="custom_url" checked>
                            <label class="form-check-label" for="customURL">Custom URL</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="zipCodeUpdates" name="zip_code_updates" checked>
                            <label class="form-check-label" for="zipCodeUpdates">Zip Code Availability Updates</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="dataUpdates" name="data_updates" checked>
                            <label class="form-check-label" for="dataUpdates">Data Updates</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="selfServiceAccess" name="self_service_access" checked>
                            <label class="form-check-label" for="selfServiceAccess">Self Service Portal Access</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="accountSupport" name="account_support" checked>
                            <label class="form-check-label" for="accountSupport">Account Management Support</label>
                        </div>
                        <div class="mb-2">
                            <label for="reporting" class="form-label">Reporting:</label>
                            <input type="text" id="reporting" name="reporting" class="form-control" value="Weekly">
                        </div>
                        <div class="mb-2">
                            <label for="maxClicks" class="form-label">Maximum Allowed Clicks:</label>
                            <input type="text" id="maxClicks" name="max_clicks" class="form-control" value="up to 1,250/month">
                        </div>
                        <div class="mb-2">
                            <label for="clickAddon" class="form-label">Maximum Click Monthly Add-on:</label>
                            <input type="text" id="clickAddon" name="click_addon" class="form-control" value="$1,500 for 750 clicks (limit of 2,000 total clicks/mo)">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Features</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
