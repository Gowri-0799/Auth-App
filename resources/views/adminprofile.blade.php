@extends('layouts.default')

@section('title', 'Change Password')

@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 250px; width: calc(100% - 220px);">
    <div class="container-fluid mt-3">
    <h5 class="fw-bold"><strong>Profile</strong>    </h5>
      <!-- Profile Section -->
<div class="card border-0 rounded-lg mb-4" style="max-width: 600px;"> <!-- Increased card width -->
   <div class="card-body bg-clearlink">
      <div class="d-flex flex-column ps-3"> <!-- Added padding-left for right alignment -->
         <!-- Admin Name -->
         <div class="d-flex align-items-center mb-3"> <!-- Increased bottom margin for spacing -->
            <i class="fa fa-user text-primary" style="margin-right: 12px;" aria-hidden="true"></i> <!-- Slightly increased icon margin -->
            <strong>{{ $admin->admin_name ?? '' }}</strong>
         </div>
         <!-- Admin Email -->
         <div class="d-flex align-items-center">
            <i class="fa fa-envelope text-primary" style="margin-right: 12px;" aria-hidden="true"></i> <!-- Slightly increased icon margin -->
            <strong>{{ $admin->email ?? '' }}</strong>
         </div>
      </div>
   </div>
</div>



        <!-- Change Password Section -->
        <div class="card shadow-sm border-0 rounded-lg" style="max-width: 600px;">
            <div class="card-body bg-clearlink">
                <h5 class="fw-bold">Change Password</h5>
                <br>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('adpro.password.update') }}" method="post">
    @csrf
    <input type="hidden" name="email" value="{{ $admin->email ?? '' }}">
    <div class="mb-3 position-relative">
       <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current password" required />
       <i class="fa fa-eye toggle-password" data-target="#current_password" aria-hidden="true" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
       </div>

    <div class="mb-3 position-relative">
        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New password" required />
        <i class="fa fa-eye toggle-password" data-target="#new_password" aria-hidden="true" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
    </div>

    <div class="mb-3 position-relative">
        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Confirm password" required />
        <i class="fa fa-eye toggle-password" data-target="#new_password_confirmation" aria-hidden="true" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
    </div>

    <input type="submit" class="btn btn-primary px-3 py-2 rounded popup-element" value="Update Password">
</form>
                <div class="text-dark popup-element">
                        <h4 class="fw-bold">Password Instructions:</h4>
                        <ul id="billing" class="">
                            <li class="billing">
                                The password should have a minimum length of 6 characters
                            </li>
                            <li class="billing">
                                The password should contain at least one letter
                            </li>
                            <li class="billing">
                                The password should contain at least one number
                            </li>
                            <li class="billing">
                                The password should contain at least one symbol (special character)
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.toggle-password').forEach((icon) => {
        icon.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('data-target'));
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>


@endsection

