<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

// Route::view(uri: "/", view: "auth.login")->name("login");

    
Route::middleware(['auth:web'])->group(function () {
    // Route::view(uri: "/", view: "auth.login")->name("login");
    Route::get('/subscriptions', [App\Http\Controllers\ZohoController::class, 'showplan'])->name('showplan');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [ZohoController::class, 'plandb'])->name('admin.dashboard');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("/admin/login", [App\Http\Controllers\AdminController::class, "showLoginForm"])
    ->name("adminlogin");

Route::get("/login", [AuthController::class, "login"])
    ->name("login");

Route::post("/login", [AuthController::class, "loginPost"])
    ->name("login.post");

Route::post("/admin", [AdminController::class, "login"])
    ->name("admin.post");

Route::get("/register", [AuthController::class, "register"])
    ->name("register");

Route::post("/register", [AuthController::class, "registerPost"])
    ->name("register.post");

Route::get("/logout", [AuthController::class, "logout"])
    ->name("logout");

Route:: get("/plantest", [ZohoController::class, "plantest"])
    ->name("plantest");
    
    Route:: get("/plan", [ZohoController::class, "plandb"])
    ->name("plandb");
    Route::get('plans/create', [ZohoController::class, 'create'])
    ->name('plans.create');
    Route::post('plans', [ZohoController::class, 'storeplan'])
    ->name('plans.store');
Route:: get("/cust", [ZohoController::class, "cust"])
    ->name("cust");

Route:: get("/in", [AuthController::class, "index"])
    ->name("in");

Route::get('/plans', [ZohoController::class, "getAllPlans"])
    ->name('plans');
Route:: get("/SupportTicket", [ZohoController::class, "supportticket"])
    ->name("Support.Ticket");

 Route::get('/customer/details', [ZohoController::class, 'showCustomerDetails'])
     ->name('customer.details');

     Route::put('/customers/{zohocust_id}/update-address', [ZohoController::class, 'addupdate'])->name('customers.addupdate');


Route::get('/customers',[ZohoController::class, "getAllCustomers"])
    ->name("customers");

Route::get('/tokens', function () {
    return view('generateaccesstoken'); })->name('tokens');


Route::post('/generate-access-token', [ZohoController::class, 'generateAccessTokenAndStore'])
    ->name('generate.access.token');

// Route::get('/zoho/callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');

Route::get('/custdetail', [AuthController::class, 'customerdetail'])
->name('custdetail');

Route::post('/customerdetail', [ZohoController::class, 'store'])->name('customers.store');

Route::get('/customer',[ZohoController::class,'display']) ->name('cust.display');

Route::get('/subscribe/{planId}', [ZohoController::class, 'subscribe'])->name('subscribe');

Route::get('/payments/{zoho_cust_id}', [ZohoController::class, 'editPayment'])->name('payments.edit');

Route::get('/showsubscription',[Zohocontroller::class,'showsubscription'])->name('subdata');
Route::get('/showinvoice',[Zohocontroller::class,'showinvoice'])->name('invdata');

Route::get('/thankyou', [ZohoController::class, 'retrieveHostedPage'])->name('thankyou');
Route::get('/thanks', [ZohoController::class, 'retrieveRetHostedPage'])->name('thanks');


Route::get('/customer/subscriptions', [ZohoController::class, 'showCustomerSubscriptions'])->name('customer.subscriptions');
Route::get('/customer/invoices', [ZohoController::class, 'showCustomerInvoices'])->name('customer.invoices');
Route::get('/customer/Credites', [ZohoController::class, 'showCustomerCredits'])->name('customer.credites');
Route::get('/customer/supports', [ZohoController::class, 'showCustomerSupport'])->name('customer.support');


Route::get('/customers/{zohocust_id}/edit', [ZohoController::class, 'edit'])->name('customers.edit');

Route::put('/customers/{id}', [ZohoController::class, 'update'])->name('customers.update');

Route::get('/profile',[ZohoController::class,'profile'])->name('profile');
Route::get('/zoho/payment/callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');
Route::get('/zoho/payment', [ZohoController::class, 'callback'])->name('zoho.call');

Route::post('/upgrade-subscription', [ZohoController::class, 'upgrade'])->name('upgrade.subscription');

Route::get('/upgrade-custsubscription', [ZohoController::class, 'custsupgrade'])->name('custsupgrade');

Route::get('/invoices/filter', [ZohoController::class, 'filterInvoices'])->name('invoices.filter');
Route::get('/creditnotes/filter', [ZohoController::class, 'filtercredits'])->name('creditnotes.filter'); // Filter credit notes

Route::post('/tickets/store', [ZohoController::class, 'ticketstore'])->name('tickets.store');
Route::get('/support', [ZohoController::class, 'showCustomerSupport'])->name('show.support');
Route::post('/downgrade-plan', [ZohoController::class, 'downgrade'])->name('downgrade_plan');
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
