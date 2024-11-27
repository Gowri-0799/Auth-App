<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\PartnerUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

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
        $request->validate([
            "email" => "required", 
            "password" => "required_if:resend_otp,0"
        ]);
    
        $credentials = $request->only("email", "password");
        $partnerUser = PartnerUser::where("email", $credentials["email"])->first();
   
        if ($request->input('resend_otp') == '1') {
            if ($partnerUser) {
                $otp = rand(100000, 999999); 
                Session::put('otp', $otp);
                Mail::to($partnerUser->email)->send(new OtpMail($otp, $partnerUser->first_name));
    
                return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
            } else {
                return redirect()->back()->withErrors(['email' => 'Email not found']);
            }
        }
    
        if ($partnerUser && Hash::check($credentials['password'], $partnerUser->password)) {
            Auth::guard('web')->login($partnerUser);
            Session::put('user_email', $credentials["email"]);

            $customer = Customer::where('zohocust_id', $partnerUser->zoho_cust_id)->first();
    
            if ($customer && $customer->first_login) {
               
                $email = $credentials["email"];
                return redirect()->route('password.reset', compact('email'));
            }
            $otp = rand(100000, 999999); 
            Session::put('otp', $otp);
            Mail::to($partnerUser->email)->send(new OtpMail($otp, $partnerUser->first_name));
            return redirect()->route('otppage');
        }

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
    $customer = PartnerUser::where('email', $email)->first();

    if ($customer) {
        $otp = rand(100000, 999999); // Generate a new OTP
        Session::put('otp', $otp); // Store the new OTP

        // Resend OTP email
        Mail::to($customer->email)->send(new OtpMail($otp, $customer->first_name));
        
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

    public function sendPasswordResetEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);
   
    $credentials = $request->only("email");
        $customer = PartnerUser::where("email", $credentials["email"])->first();

        
    if ( $customer) {
        
        $token = Str::random(60); 
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $customer->email]);

        Mail::to( $customer->email)->send(new ResetPasswordMail($customer->first_name,$resetUrl));

        return redirect(route("emailsend"))->with("success", "Register successfully");
    } else {
        return back()->withErrors(['email' => 'This email does not exist in our records.']);
    }
}

function emailsend(){
    return view("auth.emailsend");
}

public function resetlink(Request $request)
{
    $token = $request->query('token');
    $email = $request->query('email');
   
    return view("emails.resetlink", compact('token', 'email'));
   
}
public function updatePassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $partnerUser = PartnerUser::where('email', $request->email)->first();

    if (!$partnerUser) {
        return redirect()->back()->withErrors(['email' => 'Invalid email or token.']);
    }

    $partnerUser->password = Hash::make($request->password);
    
    if( $partnerUser->save()){
       
            return redirect()->route('showplan')->with('success', 'Your password has been successfully updated.');
    }

    return redirect()->route('login')->with('success', 'Your password has been successfully updated.');
}


function provider(){
    return view("provider");
}
}
