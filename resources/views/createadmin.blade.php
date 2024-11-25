@extends("layouts.default")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-dark">Enter Admin Details</h2>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="row">
                      
                        <div class="col-md-6 mb-3">
                            <label for="adminName" class="form-label fw-bold">Admin Name*</label>
                            <input type="text" name="admin_name" id="adminName" class="form-control" placeholder="Admin Name*" style="width: 90%;" required>
                        </div>
                        
                     
                        <div class="col-md-6 mb-3">
                            <label for="adminEmail" class="form-label fw-bold">Admin Email*</label>
                            <input type="email" name="admin_email" id="adminEmail" class="form-control" placeholder="Admin Email*" style="width: 90%;" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="adminRole" class="form-label fw-bold">Admin Role*</label>
                            <select name="admin_role" id="adminRole" class="form-select" style="width: 90%;" required>
                                <option value="" disabled selected>Select Role*</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                                
                            </select>
                        </div>
                        
                     
                        <div class="col-md-6 mb-3 d-flex align-items-center">
    <label for="mailNotifications" class="form-label fw-bold me-2">Receive Mail Notifications*</label>
    <input type="checkbox" name="receive_notifications" id="mailNotifications" class="form-check-input">
</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Invite</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
