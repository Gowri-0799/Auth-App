<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    protected function guard()
    {
        return Auth::guard('web');  
    }

    public function login(Request $request)
    {
         if ($request->has("is_upgrade")) {
	            Session::put("is_upgrade", $request->is_upgrade);
	            if (Auth::check()) {
	                return redirect()->route("upgrade.preview", [
	                    "plan_code" => $request->plan_code,
	                ]);
	            }
        }
        
        if ($request->has("plan_code")) {
            Session::put("plan_code", $request->plan_code);
            if (Auth::check()) {
                return redirect()->route("preview.subscribe", [
                    "plan_code" => $request->plan_code,
                ]);
            }
        }

        if ($request->has("email")) {
            Session::put("email", $request->email);       
        }
        
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

            
        
            if (empty($partnerUser->userLastLoggedin) || $partnerUser->userLastLoggedin == '0000-00-00 00:00:00') {
                $email = $credentials["email"];
                return redirect()->route('password.reset', compact('email'));
            } else {
                $otp = rand(100000, 999999);
                Session::put('otp', $otp);
                Mail::to($partnerUser->email)->send(new OtpMail($otp, $partnerUser->first_name));
                return redirect()->route('otppage')->with('success', 'A one-time password (OTP) verification code has been sent to your email. Please check your email and enter the code.');
            }
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
            // Check for upgrade flow
            if (Session::has('plan_code')) {
                $planCode = Session::get('plan_code');
    
                if (Session::get('is_upgrade') === 'yes') {
                    return redirect()->route('upgrade.preview', ['plan_code' => $planCode]);
                }
    
                // Redirect to subscription preview
                return redirect()->route('preview.subscribe', ['plan_code' => $planCode]);
            }
    
            // Normal case: Redirect to showplan
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
    $partnerUser->userlastloggedin = now();

    if ($partnerUser->save()) {
        Partner::where('zohocust_id', $partnerUser->zoho_cust_id)
            ->update(['status' => 'active']);

        return redirect()->route('showplan')->with('success', 'Your password has been successfully updated.');
    }

    return redirect()->route('login')->withErrors(['error' => 'Failed to update password.']);
}



function provider(){
    return view("provider");
}
}
