@extends("layouts.login")
@section('title', "Login")
@section("content")
<body>
   <div id="overlay"></div>
   <div class="scrollable">
      <nav class="bg-clearlink d-flex justify-content-between">
         <div class="container-fluid">
         <span class="navbar-brand p-1"><img width="150" height="75" src="/assets/images/Ln_logo.png" alt="Testlink Logo"></span>
         </div>
      </nav>
      <div class="main mb-5">
         <div class="container d-flex justify-content-center align-items-center flex-column mt-5">
            <h3 class="text-primary mt-5 mb-3">
            Testlink ISP Billing Admin Portal</h3>
            @if(session()->has("success"))
            <div class="alert alert-success">
               {{session()->get("success")}}
            </div>
            @endif
            @if(session()->has("error"))
            <div class="alert alert-danger">
               {{session()->get("error")}}
            </div>
            @endif
            <div class="card p-4 w-100 shadow-sm border-0 rounded login-card bg-clearlink ">
               <div class="rounded p-2">
                  <form method="POST" action="{{route("admin.post")}}">
                  @csrf
                  <div class="form-group mb-3">
                     <label for="email" class="form-label fw-bold">Email address</label>
                     <input type="text" placeholder="Enter Email" id="email" class="form-control" name="email"
                        required autofocus>
                     @if($errors->has('email'))
                     <span class="text-danger">
                     {{$errors->first('email')}}
                     </span> 
                     @endif
                  </div>
                  <div class=" mb-3">
                     <label for="password" class="form-label fw-bold">Password</label>
                     <div class="input-group">
                        <input type="password" placeholder="Enter Password" id="password" class="form-control border-end-0 shadow-none"
                           name="password" required>
                        <span class="input-group-text bg-white border-start-0" style="cursor: pointer;">
                        <i class="fas password-toggle-icon fa-eye"></i>
                        </span>
                     </div>
                     @if($errors->has('password'))
                     <span class="text-danger">
                     {{$errors->first('password')}}
                     </span>
                     @endif
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                     <button class="btn btn-primary" type="submit">Login</button>
                     <a href="{{ route('admin.password.request') }}" class="text-decoration-none text-primary">Forgot password?</a>
                  </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <footer class="footer p-4 bg-clearlink w-100 d-flex justify-content-center position-fixed position-absolute text-center bottom-0 align-items-center ">
         @ Testlink Technologies 2024
      </footer>
   </div>
   
   <!-- Ensure jQuery is included -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      $(document).ready(function() {
         // Click event to toggle password visibility
         $('#togglePassword').click(function() {
            const passwordField = $('#password');
            const passwordFieldType = passwordField.attr('type');

            if (passwordFieldType === 'password') {
               passwordField.attr('type', 'text');
               $(this).removeClass('fa-eye').addClass('fa-eye-slash'); // Change to eye-slash icon
            } else {
               passwordField.attr('type', 'password');
               $(this).removeClass('fa-eye-slash').addClass('fa-eye'); // Change back to eye icon
            }
         });
      });
   </script>
</body>
@endsection
