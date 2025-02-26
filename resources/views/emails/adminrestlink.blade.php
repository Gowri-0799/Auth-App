@extends("layouts.login")
@section('title', "Reset Password")
@section("content")
<nav class="bg-clearlink d-flex justify-content-between">
   <div class="container-fluid">
      <span class="navbar-brand p-1"><img width="150" height="37" src="/assets/images/Ln_logo.png" alt="Testlink Logo"></span>
   </div>
</nav>
<div class="main mb-5">
   <div class="container d-flex justify-content-center align-items-center flex-column mt-5">
      <h3 class="text-primary mb-3">Teslink ISP Admin Program Change Password</h3>
      <div style="margin-bottom: 100px;" class="card p-4 w-100 shadow-sm border-0 rounded login-card bg-clearlink">
         <div class="rounded p-2">
            <form method="POST" action="{{ route('admin.password.update') }}">
               @csrf
               <!-- Hidden input fields for form submission -->
               <input type="hidden" name="email" value="{{ $email }}">
               <div class="form-group mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="border border-dark rounded d-flex flex-row align-items-center">
                     <input type="password" placeholder="Enter Password" id="password1" class="form-control border-0 shadow-none" name="password" required>
                     <span style="cursor: pointer;" class=" me-2"><i class="password-toggle-icon1 fas fa-eye"></i></span>
                  </div>
                  <span class="text-danger"></span>
               </div>
               <div class="form-group mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="border border-dark rounded d-flex flex-row align-items-center">
                     <input type="password" placeholder="Confirm Password" id="password" class="form-control border-0 shadow-none" name="password_confirmation" required>
                     <span style="cursor: pointer;" class=" me-2"><i class="password-toggle-icon fas fa-eye"></i></span>
                  </div>
                  <span class="text-danger"></span>
               </div>
               <div class="form-group mb-3 d-flex justify-content-start align-items-center">
                  <button class="btn btn-primary" type="submit">Change Password</button>
               </div>
            </form>
            <div class="mt-5">
               <p class="fs-6 fw-bold">Password Instructions</p>
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

 <footer class="footer p-4 bg-clearlink w-100 d-flex justify-content-center text-center bottom-0 align-items-center ">
    @ Testlink Technologies 2024
</footer>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const passwordField = document.getElementById("password1");
    const confirmPasswordField = document.getElementById("password");
    const errorSpan = document.querySelector(".text-danger");

    function validatePassword() {
        const password = passwordField.value;
        let errors = [];

        // Check for minimum length of 6 characters
        if (password.length < 6) {
            errors.push("Password must be at least 6 characters.");
        }
        
        // Check for at least one letter
        if (!/[a-zA-Z]/.test(password)) {
            errors.push("Password must contain at least one letter.");
        }
        
        // Check for at least one number
        if (!/[0-9]/.test(password)) {
            errors.push("Password must contain at least one number.");
        }
        
        // Check for at least one special character
        if (!/[@$!%*?&]/.test(password)) {
            errors.push("Password must contain at least one special character.");
        }
        
        // Display all errors, or clear if none
        errorSpan.textContent = errors.join(" ");
    }

    function validateConfirmPassword() {
        if (passwordField.value !== confirmPasswordField.value) {
            errorSpan.textContent = "Passwords do not match.";
        } else {
            validatePassword(); // Run complexity validation if passwords match
        }
    }

    passwordField.addEventListener("input", validatePassword);
    confirmPasswordField.addEventListener("input", validateConfirmPassword);
});
</script>
@endsection