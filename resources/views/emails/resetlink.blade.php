@extends("layouts.login")
@section('title', "Reset Password")
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
            <h3 class="text-primary mt-5 mb-3">Testlink ISP Partner Program Change Password</h3>

            <div class="card p-4 w-100 shadow-sm border-0 rounded login-card bg-clearlink">
               <div class="rounded p-2">
                  <form method="POST" action="#">
                     @csrf
                     <input type="hidden" name="token" value="{{ $token }}">
                     <input type="hidden" name="email" value="{{ $email }}">
                     <div class="form-group mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                           <input type="password" placeholder="Enter Password" id="password" class="form-control" name="password" required>
                           <span class="input-group-text"><i class="fa fa-eye" onclick="togglePassword('password')"></i></span>
                        </div>
                     </div>
                     <div class="form-group mb-3">
                        <label for="confirm_password" class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                           <input type="password" placeholder="Confirm Password" id="confirm_password" class="form-control" name="password_confirmation" required>
                           <span class="input-group-text"><i class="fa fa-eye" onclick="togglePassword('confirm_password')"></i></span>
                        </div>
                     </div>
                     <div class="form-group d-flex justify-content-center align-items-center">
                        <button class="btn btn-primary" type="submit">Change Password</button>
                     </div>
                     <div class="mt-3">
                        <h5 class="fw-bold">Password Instructions</h5>
                        <ul>
                           <li>The password should have a minimum length of 6 characters</li>
                           <li>The password should contain at least one letter</li>
                           <li>The password should contain at least one number</li>
                           <li>The password should contain at least one symbol (special character)</li>
                        </ul>
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

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
      function togglePassword(fieldId) {
         const field = document.getElementById(fieldId);
         if (field.type === "password") {
            field.type = "text";
         } else {
            field.type = "password";
         }
      }
   </script>
</body>
@endsection
