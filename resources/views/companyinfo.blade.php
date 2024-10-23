@extends("layouts.admin")
@section('title', "Company Info")

@section('content')
<div id="content" style="box-sizing: border-box; margin-left:300px; width:100%" class="p-3">
    <div class="mb-2 w-100">
        <h2 class="mt-2 mb-5">Company Info</h2>
    </div>
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4 row">
            <div class="col-lg-2">
                <label for="logo" class="form-label fw-bold">Logo*</label>
            </div>
            <div class="col-lg-6">
                <label for="uploadImage" class="image-upload-btn text-primary fw-bold">Update Logo</label>
                <input type="file" id="uploadImage" name="logo" accept="image/*" />
                <span class="text-danger"> </span>
            </div>
        </div>

        <div class="mb-4 row">
            <div class="col-lg-4">
                <label for="company_name" class="form-label fw-bold">Company name for display on site*</label>
                <input name="company_name" class="form-control" value="" required>
                <span class="text-danger"> </span>
            </div>

            <div class="col-lg-4 mb-4">
                <label for="tune_link" class="form-label fw-bold">Tune Link*</label>
                <input name="tune_link" value="" class="form-control" required>
                <span class="text-danger"> </span>
            </div>
        </div>

        <!-- Flexbox for aligned inputs -->
        <div class="row">
            <div class="col-lg-4 d-flex flex-column">
                <label for="landing_page_url" class="form-label fw-bold">Landing Page Url* 
                    <span class="body-text-small fw-normal">(The page where you want customers to land on your site.)</span>
                </label>
                <input name="landing_page_url" class="form-control" value="" required>
                <span class="text-danger"> </span>
                <p class="body-text-small mt-1">Enter the full URL, including 'http://' or 'https://'. For example, 'https://www.example.com'.</p>
            </div>

            <div class="col-lg-4 d-flex flex-column">
                <label for="landing_page_url_spanish" class="form-label fw-bold">Landing Page Url (Spanish) 
                    <span class="body-text-small fw-normal">(The page where you want Spanish-speaking customers to land on your site.)</span>
                </label>
                <input name="landing_page_url_spanish" class="form-control" value="">
                <span class="text-danger"> </span>
                <p class="body-text-small mt-1">Enter the full URL, including 'http://' or 'https://'. For example, 'https://www.example.com'.</p>
            </div>
        </div>

        <input type="submit" class="btn btn-primary text-end text-white px-3" value="Update Data">
        <a class="btn button-clearlink text-primary fw-bold ms-3" data-bs-toggle="modal" data-bs-target="#sendDetailModal"> Send Details To Admin</a>
    </form>
</div>
@endsection
