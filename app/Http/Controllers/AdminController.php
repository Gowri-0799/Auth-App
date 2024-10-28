<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct()
    {
       //$this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.admin');  // Create a separate login view for admins
    }

    public function login(Request $request)
{
    $request->validate(["email" => "required", "password" => "required_if:resend_otp,0"]);

    $credentials = $request->only("email", "password");
    $admin = Admin::where('email', $credentials['email'])->first();

    // Check if resend OTP was requested
    if ($request->input('resend_otp') == '1') {
        // Resend OTP logic
        if ($admin) {
            $otp = rand(100000, 999999); // Generate a new OTP
            Session::put('otp', $otp);

            // Send OTP email
            Mail::to($admin->email)->send(new OtpMail($otp, $admin->name));

            return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
        } else {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }
    }

    // Regular login flow
    if ($admin && Hash::check($credentials['password'], $admin->password)) {
        Auth::guard('admin')->login($admin);
        Session::put('user_email', $credentials["email"]);
        
        // Generate OTP and send email
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Mail::to($admin->email)->send(new OtpMail($otp, $admin->name));
        
        return redirect()->route('adminotppage'); // Redirect to OTP verification page
    }

    // If authentication fails
    return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
}

public function adminverifyOtp(Request $request)
{
    $request->validate([
        "email" => "required",
        "otp" => "required"
    ]);

    $sessionOtp = Session::get('otp');
    $inputOtp = $request->input('otp');

    // Check if the session OTP exists and matches the input OTP
    if ($sessionOtp && $sessionOtp == $inputOtp) {
        // OTP is valid, redirect to admin dashboard or intended route
        return redirect()->route('admin.dashboard'); // Change to your desired route
    }

    // If OTP is invalid, redirect back with an error message
    return redirect()->back()->with('error', 'Invalid OTP, please try again.');
}

public function adminresendOtp(Request $request)
{
    // Get the email from the session
    $email = Session::get('user_email');
    // Find the admin using the email
    $admin = Admin::where('email', $email)->first();

    // Check if the admin exists
    if ($admin) {
        $otp = rand(100000, 999999); // Generate a new OTP
        Session::put('otp', $otp); // Store the new OTP

        // Resend OTP email
        Mail::to($admin->email)->send(new OtpMail($otp, $admin->name)); // Make sure to adjust this based on your Admin model

        return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
    } else {
        return redirect()->back()->withErrors(['email' => 'Email not found']);
    }
}

function adminotppage(){
    return view("auth.adminotp");
 }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
