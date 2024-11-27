@extends("layouts.admin")
@section('title', "Company Info")

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:100%" class="p-3">
    <div class="mb-2 w-100">
        <h2 class="mt-2 mb-5">Company Info</h2>
    </div>
    <form action="{{ route('company-info.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">
        
 <!-- Logo Upload Section -->
<div class="mb-4 row">
<div class="col-lg-2">
    @if($customer->first_login == 1)
        <!-- Show 'Upload Logo' button when first login is 1 (logo not uploaded yet) -->
        <label for="uploadImage" class="image-upload-btn text-primary fw-bold">Upload Logo</label>
    @else
        <!-- Once first login is 0 and logo is saved, show the 'Logo*' label and the current logo -->
        <label for="logo" class="form-label fw-bold">Logo*</label>
        <img src="{{ asset('storage/' . $company->logo_image ??'') }}" alt="logo" style="width:125px;" id="logoPreview" />
    @endif
</div>

    <div class="col-lg-6">
        @if($customer->first_login == 1)
            <!-- Show only 'Upload Logo' button and allow uploading -->
            <input type="file" id="uploadImage" name="logo" accept="image/*" onchange="previewLogo(event)" />
            
        @else
            <!-- Show 'Update Logo' button after first login -->
            <label for="uploadImage" class="image-upload-btn text-primary fw-bold">Update Logo</label>
            
            <input type="file" id="uploadImage" name="logo" accept="image/*" onchange="previewLogo(event)" />
        @endif

        <!-- Show validation errors -->
        <span class="text-danger">@error('logo'){{ $message }}@enderror</span>
    </div>
</div>

<!-- Add Image Preview Below the Upload Button -->
<div class="mb-4 row">
    <div class="col-lg-2">
        <img id="logoPreview" src="" alt="Logo Preview" style="width: 125px; display: none;" />
    </div>
</div>

       <!-- Company Information Section -->
<div class="mb-4 row">
    <div class="col-lg-4">
        <label for="company_name" class="form-label fw-bold">Company name for display on site*</label>
        <input name="company_name" class="form-control" value="{{ $customer->company_name ?? '' }}" required>
        <span class="text-danger">@error('company_name'){{ $message }}@enderror</span>
    </div>

    <div class="col-lg-4 mb-4">
        <label for="tune_link" class="form-label fw-bold">Tune Link*</label>
        <input name="tune_link" value="{{ $company->tune_link ?? '' }}" class="form-control" required>
        <span class="text-danger">@error('tune_link'){{ $message }}@enderror</span>
    </div>
</div>

        <!-- Landing Page URLs -->
        <div class="row">
            <div class="col-lg-4 d-flex flex-column">
                <label for="landing_page_url" class="form-label fw-bold">Landing Page Url*
                    <span class="body-text-small fw-normal">(The page where you want customers to land on your site.)</span>
                </label>
                <input name="landing_page_url" class="form-control" value="{{ $company->landing_page_uri ?? '' }}" required>
                <span class="text-danger">@error('landing_page_url'){{ $message }}@enderror</span>
                <p class="body-text-small mt-1">Enter the full URL, including 'http://' or 'https://'. For example, 'https://www.example.com'.</p>
            </div>

            <div class="col-lg-4 d-flex flex-column">
                <label for="landing_page_url_spanish" class="form-label fw-bold">Landing Page Url (Spanish)
                    <span class="body-text-small fw-normal">(The page where you want Spanish-speaking customers to land on your site.)</span>
                </label>
                <input name="landing_page_url_spanish" class="form-control" value="{{ $company->landing_page_url_spanish ?? '' }}">
                <span class="text-danger">@error('landing_page_url_spanish'){{ $message }}@enderror</span>
                <p class="body-text-small mt-1">Enter the full URL, including 'http://' or 'https://'. For example, 'https://www.example.com'.</p>
            </div>
        </div>

@if ($customer->first_login == 1)
<input type="submit" class="btn btn-primary text-end text-white px-3" value="Save">
@else
    <input type="submit" class="btn btn-primary text-end text-white px-3" value="Update Data">
    <a class="btn button-clearlink text-primary fw-bold ms-3" data-bs-toggle="modal" data-bs-target="#sendDetailModal">Send Details To Admin</a>
@endif
       
    </form>
</div>
  <!-- JavaScript to Preview Logo -->
  <script>
    function previewLogo(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('logoPreview');
            output.src = reader.result;
            output.style.display = 'block';  // Make the preview visible
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<!-- Send Details Modal -->
<div class="modal fade" id="sendDetailModal" tabindex="-1" aria-labelledby="sendDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendDetailModalLabel">Send Details to Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to send the company details to the admin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection
