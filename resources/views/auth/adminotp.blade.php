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
            <h3 class="text-primary mt-5 mb-3">Testlink ISP Admin Program OTP Verification</h3>
            
            @if(session()->has("success"))
            <div class="alert alert-success">{{ session()->get("success") }}</div>
            @endif
            
            @if(session()->has("error"))
            <div class="alert alert-danger">{{ session()->get("error") }}</div>
            @endif

            <div class="card p-4 w-100 shadow-sm border-0 rounded login-card bg-clearlink">
               <div class="rounded p-2">
                  <form method="POST" action="{{ route('adminverify.otp') }}">
                     @csrf
                     <div class="form-group mb-3">
                        <label for="email" class="form-label fw-bold">Email ID</label>
                        <input type="text" placeholder="Enter Email" id="email" class="form-control" name="email" value="{{ session('user_email') }}" required readonly>
                        @if($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                     </div>
                     <div class="form-group mb-3">
                        <label for="otp" class="form-label fw-bold">Enter OTP</label>
                        <input type="text" placeholder="Enter OTP" id="otp" class="form-control" name="otp" required>
                        @if(session('error'))
                        <span class="text-danger">{{ session('error') }}</span>
                        @endif
                     </div>
                     <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <button class="btn btn-primary" type="submit">Verify OTP</button>
                       
                        <a href="#" class="text-decoration-none text-primary" onclick="resendOtp()">Resend OTP</a>

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
   <script>
      function resendOtp() {
         // Create a form for resending OTP and submit it to a different route
         const form = document.createElement('form');
         form.method = 'POST';
         form.action = '{{ route("adminresend.otp") }}';  // Route for resending OTP
         form.style.display = 'none';

         // Add CSRF token
         const csrfInput = document.createElement('input');
         csrfInput.name = '_token';
         csrfInput.value = '{{ csrf_token() }}';
         form.appendChild(csrfInput);

         // Append form to the body and submit
         document.body.appendChild(form);
         form.submit();
      }
   </script>
</body>
@endsection
