@extends("layouts.login")
@section('title', "Reset Password")
@section("content")
<body>
   <div id="overlay"></div>
   <div class="scrollable">
      <nav class=" bg-clearlink d-flex justify-content-between">
      <div class="container-fluid">
            <span class="navbar-brand p-1"><img width="150" height="75" src="/assets/images/Ln_logo.png" alt="Testlink Logo"></span>
         </div>
      </nav>
      <div class="main mb-5">
         <div class="container d-flex justify-content-center align-items-center flex-column pt-5">
            <h3 class="text-primary mt-5 mb-3">Reset your password</h3>
            <div class="card p-4 w-100 shadow-sm border-0 rounded login-card bg-clearlink">
               <!-- Adjusted card size -->
               <div class="rounded p-2">
               <form action="{{ route('adminlogin') }}" >
    @csrf
    <div class="mb-3 fw-bold">
        <!-- Reduced font size for description -->
        Please check your email for a password reset link. If you don’t see it within a few minutes, be sure to check your spam folder.
    </div>
   
    <div class="form-group mb-3 d-flex justify-content-start align-items-center">
        <button class="btn btn-primary" type="submit" style="font-size: 0.9rem; padding: 6px 12px;">Return to login</button>
    </div>
</form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <footer class="footer p-4 bg-clearlink w-100 d-flex justify-content-center position-fixed position-absolute text-center bottom-0 align-items-center ">
      @ Testlink Technologies 2024
   </footer>
   </div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
   <script type="text/javascript" src="/assets/js/plan.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>
@endsection