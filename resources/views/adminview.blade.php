@extends("layouts.default")
@section('title', "Admins")

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
        <!-- Card Wrapper for the Table -->
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="font-size: 30px;">Admins</h2>
                <a href="" class="btn btn-primary" style="font-family: Arial, sans-serif; font-size: 14px;">Invite an Admin</a>
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
                        <button class="btn button-clearlink text-primary fw-bold" type="submit" style="font-family: Arial, sans-serif; font-size: 14px;">Submit</button>
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
                                    <td> </td>
                                    <td>{{ $admin->mail_notifications ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-primary">Edit</a>
                                        
                                    </td>
                                    <td>
                                    <form action="" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn button-clearlink text-primary fw-bold">Delete</button>
                                        </form>
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
@endsection
