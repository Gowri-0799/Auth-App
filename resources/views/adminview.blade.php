@extends("layouts.default")
@section('title', "Admins")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc;">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="font-size: 30px;">Admins</h2>
                <a href="{{ route('admin.invite') }}" class="btn btn-primary" >Invite an Admin</a>
            </div>

            <div class="card-body p-3">
                <!-- Table Filters Section -->
                <form class="row mb-4 align-items-end" method="GET" action="{{ route('admin.index') }}">
                    <div class="col-md-2">
                        <label for="startDate" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-control" value="{{ request('startDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="endDate" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
                        <input type="date" id="endDate" name="endDate" class="form-control" value="{{ request('endDate') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-2">
                        <label for="showEntries" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Show</label>
                        <select id="showEntries" name="show" class="form-select" style="font-family: Arial, sans-serif; font-size: 14px;">
                            <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Search</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}" style="font-family: Arial, sans-serif; font-size: 14px;">
                    </div>
                    <div class="col-md-1">
                        <button class="btn button-clearlink text-primary fw-bold" type="submit" >Submit</button>
                    </div>
                </form>

                <a href="{{ route('admin.index') }}" class="btn text-primary text-decoration-underline fw-bold p-0 pt-2" style="margin-bottom: 20px;">Reset</a>

                <!-- Check for admins -->
                @if($admins->count() == 0)
                    <div class="d-flex justify-content-center align-items-center mt-5">
                        <h3>No admins found.</h3>
                    </div>
                @else
                    <!-- Styled Bootstrap Table -->
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-bordered" style="background-color:#fff; width: 100%; max-width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th style="background-color:#EEF3FB;">S.No</th>
                                    <th style="background-color:#EEF3FB;">Name</th>
                                    <th style="background-color:#EEF3FB;">Email</th>
                                    <th style="background-color:#EEF3FB;">Role</th>
                                    <th style="background-color:#EEF3FB;">Mail Notifications</th>
                                    <th style="background-color:#EEF3FB;"></th>
                                    <th style="background-color:#EEF3FB;"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $index => $admin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->admin_name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->role }} </td>
                                    <td>{{ $admin->receive_mail_notifications ? 'Yes' : 'No' }}</td>
                                    <td>
                                    <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-sm btn-primary">Edit</a>    
                                    </td>
                                    <td>
                                     <button type="button" class="btn button-clearlink text-primary fw-bold delete-btn" data-id="{{ $admin->id }}" data-name="{{ $admin->admin_name }}">Delete</button>
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="adminName"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">OK</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
   
        const deleteButtons = document.querySelectorAll('.delete-btn');

     
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const adminId = this.getAttribute('data-id');
                const adminName = this.getAttribute('data-name');
 
                document.getElementById('adminName').innerText = adminName;

                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = '/admin/' + adminId; 

                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Update the min attribute of the End Date input when Start Date changes
        startDateInput.addEventListener('change', function () {
            const startDate = this.value; // Get selected start date
            if (startDate) {
                endDateInput.min = startDate; // Set the min attribute
            }
        });

        // Ensure the End Date is valid if already selected
        const currentStartDate = startDateInput.value;
        if (currentStartDate) {
            endDateInput.min = currentStartDate;
        }
    });
</script>
@endsection
