<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ZohoController;
use Illuminate\Support\Facades\Route;

//Route::view(uri: "/", view: "auth.login")->name("login");

Route::middleware("auth")->group(function () {
    Route::get("/", [AuthController::class, "adminlogin"])
        ->name("adminlogin");
    //Route::view(uri: "/home", view: "welcome")->name("home");
    Route::get('/home', [ZohoController::class, "getAllPlans"])
        ->name('home');
});

Route::middleware(["auth", "usertype"])->group(function () {
    Route::view(uri: "/adminhome", view: "adminwelcome")->name("adminhome");
});

Auth::routes();

Route::get("/adminlogin", [AuthController::class, "adminlogin"])
    ->name("adminlogin");

Route::get("/login", [AuthController::class, "login"])
    ->name("login");

Route::post("/login", [AuthController::class, "loginPost"])
    ->name("login.post");

Route::get("/register", [AuthController::class, "register"])
    ->name("register");

Route::post("/register", [AuthController::class, "registerPost"])
    ->name("register.post");

Route::get("/logout", [AuthController::class, "logout"])
    ->name("logout");

Route:: get("/plan", [ZohoController::class, "plan"])
    ->name("plan");

Route:: get("/cust", [ZohoController::class, "cust"])
    ->name("cust");

Route:: get("/in", [AuthController::class, "index"])
    ->name("in");

Route::get('/plans', [ZohoController::class, "getAllPlans"])
    ->name('plans');

Route::get('/customers',[ZohoController::class, "getAllCustomers"])
    ->name("customers");

Route::get('/tokens', function () {
    return view('generateaccesstoken'); })->name('tokens');


Route::post('/generate-access-token', [ZohoController::class, 'generateAccessTokenAndStore'])
    ->name('generate.access.token');

Route::get('/zoho/callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');

Route::get('/custdetail', [AuthController::class, 'customerdetail'])
->name('custdetail');

Route::post('/customerdetail', [ZohoController::class, 'store'])->name('customers.store');

Route::get('/customer',[ZohoController::class,'display']) ->name('cust.display');

