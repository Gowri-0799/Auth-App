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

class AuthController extends Controller
{

    protected function guard()
    {
        return Auth::guard('web');  // This is the default guard
    }

    public function login()
    {
        return view("auth.login");
    }

    public function adminlogin()
    {
        return view("auth.login");
    }

    function loginPost(Request $request)
    {
        $request->validate(["email" => "required", "password" => "required"]);

        $credentials = $request->only("email", "password");

        $customer = Customer::Where("Customer_email", $credentials["email"])->first();

        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            Auth::guard('web')->login($customer);
            Session::put('user_email', $credentials["email"]);
            return redirect()->route('showplan'); // Or redirect to user dashboard
        }

        // If not found in users, check in admins table
        // $admin = Admin::where('email', $credentials['email'])->first();

        // if ($admin && Hash::check($credentials['password'], $admin->password)) {
        //     Auth::guard('admin')->login($admin);
        //     return redirect()->route('admin.dashboard'); // Or redirect to admin dashboard
        // }

        // If both fail, return with error
        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);

        /*if (Auth::attempt($credentials)) {
            return redirect()->intended(route("home"));
        }
        return redirect(route("login"))->with("error", "Login failed");*/
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

}
