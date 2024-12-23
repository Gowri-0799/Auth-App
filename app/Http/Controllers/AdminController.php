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
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use App\Mail\AdminInvitation;

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

    
    if ($request->input('resend_otp') == '1') {
       
        if ($admin) {
            $otp = rand(100000, 999999); 
            Session::put('otp', $otp);

           
            Mail::to($admin->email)->send(new OtpMail($otp, $admin->name));

            return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
        } else {
            return redirect()->back()->withErrors(['email' => 'Email not found']);
        }
    }

    if ($admin && Hash::check($credentials['password'], $admin->password)) {
        Auth::guard('admin')->login($admin);
        $email = $credentials["email"];
        Session::put('user_email', $email);
        
        if ($admin->first_login) {
           
            return redirect()->route('adpassword.reset', compact('email'));
        }     
        
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Mail::to($admin->email)->send(new OtpMail($otp, $admin->name));
        
        return redirect()->route('adminotppage')->with('success', 'A one-time password (OTP) verification code has been sent to your email. Please check your email and enter the code.');; 
    }

   
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

    function adshowLinkRequestForm(){
        return view("auth.adminreset");
    }

    public function adsendPasswordResetEmail(Request $request)
    {
    // Validate the request data
    $request->validate(['email' => 'required|email']);
    $credentials = $request->only("email");

    // Find the admin by email
    $admin = Admin::where("email", $credentials["email"])->first();
    
        if ($admin) {
            // Generate a password reset token
            $token = Str::random(60); 

            // Create the reset URL
            $resetUrl = route('adpassword.reset', ['token' => $token, 'email' => $admin->email]);

            // Send the reset password email
            Mail::to($admin->email)->send(new ResetPasswordMail($admin->admin_name, $resetUrl));

            // Redirect to a success page
            return redirect(route("ademailsend"))->with("success", "Password reset email sent successfully.");
        } else {
            return back()->withErrors(['email' => 'This email does not exist in our records.']);
        }
    }

    public function adresetlink(Request $request)
    {
        $email = $request->query('email');
        return view("emails.adminrestlink", compact('email'));
    }

    function ademailsend(){
        return view("auth.adminemailsend");
    }

    public function updateAdminPassword(Request $request)
    {
      
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/[a-zA-Z]/',      
                'regex:/[0-9]/',          
                'regex:/[@$!%*?&]/'       
            ],
        ], [
            'password.regex' => 'The password must contain at least one letter, one number, and one special character.'
        ]);

        $admin = Admin::where('email', $request->email)->first();
      
        if (!$admin) {
            return redirect()->back()->withErrors(['email' => 'Invalid email or token.']);
        }
     
        $admin->password = Hash::make($request->password);
        $admin->first_login = false;

        if ($admin->save()) {
           
            if ($admin->first_login == 0) {
                return redirect()->route('admin.dashboard')->with('success', 'Your password has been successfully updated.');
            } else {
                return redirect()->route('adminlogin')->with('success', 'Your password has been successfully updated.');
            }
        }
        return redirect()->route('adminlogin')->with('success', 'Your password has been successfully updated.');
    }

    public function adminview(Request $request)
    {
        $query = Admin::query();
    
        if ($request->filled('startDate')) {
            $query->whereDate('created_at', '>=', $request->startDate);
        }
    
        if ($request->filled('endDate')) {
            $query->whereDate('created_at', '<=', $request->endDate);
        }
    
        if ($request->filled('search')) {
            $query->where('admin_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('role', 'like', '%' . $request->search . '%');
        }
   
        $perPage = $request->input('show', 10); 
        $admins = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('adminview', compact('admins'));
    }


    public function addadmin(){
        return view('createadmin');
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:admins,email',
            'admin_role' => 'required|string',
        ]);
        $randomPassword = Str::random(16);
        $admin = Admin::create([
            'admin_name' => $request->input('admin_name'),
            'email' => $request->input('admin_email'),
            'role' => $request->input('admin_role'),
            'receive_mail_notifications' => $request->has('receive_notifications') ? 1 : 0,
            'password' => bcrypt($randomPassword),
        ]);
        
        if ($admin->receive_mail_notifications) {
       
            $loginUrl = route('adminlogin'); 
            
            // Send the invitation email to the admin
            Mail::to($admin->email)->send(new AdminInvitation($admin,  $randomPassword, $loginUrl));
        }

        return redirect()->route('admin.index')->with('success', 'Admin added successfully.');
    }

    public function edit($id)
{

    $admin = Admin::findOrFail($id);
    

    return view('admin-edit', compact('admin'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'admin_name' => 'required|string|max:255',
        'admin_email' => 'required|email|unique:admins,email,' . $id,
        'admin_role' => 'required|string',
    ]);

  
    $admin = Admin::findOrFail($id);

    $randomPassword = Str::random(16);

    $admin->update([
        'admin_name' => $request->input('admin_name'),
        'email' => $request->input('admin_email'),
        'role' => $request->input('admin_role'),
        'receive_mail_notifications' => $request->has('receive_notifications') ? 1 : 0,
        'password' => bcrypt($randomPassword),
    ]);
if ($admin->receive_mail_notifications) {
        $loginUrl = route('adminlogin'); 
        
        // Send the invitation email with the new password
        Mail::to($admin->email)->send(new AdminInvitation($admin, $randomPassword, $loginUrl));
    }
    return redirect()->route('admin.index')->with('success', 'Admin updated successfully.');
}

public function destroy($id)
{
    $admin = Admin::findOrFail($id);
    $admin->delete();

    return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
}


public function adminprofile()
{
  
    $email = Session::get('user_email');

    $admin = Admin::where('email', $email)->first();

    return view('adminprofile', compact('admin'));
}

public function adminupdatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => [
            'required',
            'confirmed',
            'min:6',
            'regex:/[a-zA-Z]/',      
            'regex:/[0-9]/',          
            'regex:/[@$!%*?&]/'   
        ],
    ], [
        'new_password.required' => 'The new password field is required.',
        'new_password.confirmed' => 'The new password confirmation does not match.',
        'new_password.min' => 'The password must be at least 6 characters long.',
        'new_password.regex' => 'The password must contain at least one letter, one number, and one special character.'
    ]);
    $email = Session::get('user_email'); 
    $admin = Admin::where('email', $email)->first();

   
    if (!$admin) {
        return redirect()->back()->withErrors(['email' => 'Admin not found.']);
    }

 
    if (!Hash::check($request->current_password, $admin->password)) {
        return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }

 
    $admin->password = Hash::make($request->new_password);
    $admin->save();

    return redirect()->back()->with('success', 'Password updated successfully.');
}

}

