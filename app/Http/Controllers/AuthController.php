<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{

    protected function guard()
    {
        return Auth::guard('web');  
    }

    public function login()
    {
        return view("auth.login");
    }

    public function adminlogin()
    {
        return view("auth.login");
    }

    public function loginPost(Request $request)
    {
        $request->validate(["email" => "required", "password" => "required_if:resend_otp,0"]);
    
        $credentials = $request->only("email", "password");
        $customer = Customer::where("Customer_email", $credentials["email"])->first();
    
        if ($request->input('resend_otp') == '1') {
            // Resend OTP logic
            if ($customer) {
                $otp = rand(100000, 999999); // Generate a new OTP
                Session::put('otp', $otp);
    
                // Resend OTP email
                Mail::to($customer->customer_email)->send(new OtpMail($otp, $customer->first_name));
    
                return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
            } else {
                return redirect()->back()->withErrors(['email' => 'Email not found']);
            }
        }
    
        // Regular login flow
        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            Auth::guard('web')->login($customer);
            Session::put('user_email', $credentials["email"]);
            
            $otp = rand(100000, 999999); // Generate OTP
            Session::put('otp', $otp);
    
            // Send OTP email
            Mail::to($customer->customer_email)->send(new OtpMail($otp, $customer->first_name));
            
            return redirect()->route('otppage');
        }
    
        // If authentication fails
        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    function otppage(){
        return view("auth.otp");
     }
    
     public function verifyOtp(Request $request)
{
    $request->validate([
        "email" => "required",
        "otp" => "required"
    ]);

    $sessionOtp = Session::get('otp');
    $inputOtp = $request->input('otp');

    if ($sessionOtp && $sessionOtp == $inputOtp) {
        return redirect()->route('showplan');
    }

    return redirect()->back()->with('error', 'Invalid OTP, please try again.');
}

public function resendOtp(Request $request)
{
    $email = Session::get('user_email');
    $customer = Customer::where('customer_email', $email)->first();

    if ($customer) {
        $otp = rand(100000, 999999); // Generate a new OTP
        Session::put('otp', $otp); // Store the new OTP

        // Resend OTP email
        Mail::to($customer->customer_email)->send(new OtpMail($otp, $customer->first_name));
        
        return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
    } else {
        return redirect()->back()->withErrors(['email' => 'Email not found']);
    }
}
    function register()
    {
        return view("auth.register");
    }

    function registerPost(Request $request)
    {
        $request->validate(["name" => "required|unique:users", "email" => "required", "password" => "required"]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return redirect(route("login"))->with("success", "Register successfully");
        }
        return redirect(route("register"))->with("error", "Failed to create account");
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route("login"));
    }

    function welcome()
    {
        return view("welcome");
    }
    function adminwelcome()
    {
        return view("adminwelcome");
    }
    function showLinkRequestForm(){
        return view("auth.reset");
    }

}
