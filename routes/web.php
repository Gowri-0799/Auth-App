<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CompanyinfoController;
use Illuminate\Support\Facades\Route;

// Route::view(uri: "/", view: "auth.login")->name("login");

    
Route::middleware(['auth:web'])->group(function () {
    // Route::view(uri: "/", view: "auth.login")->name("login");
    Route::get('/verify-otp', [AuthController::class, 'otppage'])->name('otppage');
});
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');

Route::get('/customer/plan-subscriptions', [App\Http\Controllers\ZohoController::class, 'showplan'])->name('showplan');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/verify-otp', [AdminController::class, 'adminotppage'])->name('adminotppage');
});
Route::post('admin/verify-otp', [AdminController::class, 'adminverifyOtp'])->name('adminverify.otp');

Route::get('/admin/dashboard', [ZohoController::class, 'plandb'])->name('admin.dashboard');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
Route::post('admin/resend-otp', [AdminController::class, 'adminresendOtp'])->name('adminresend.otp');

// Auth::routes();
Route::post('/password/reset-email', [AuthController::class, 'sendPasswordResetEmail'])->name('password.reset.email');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("/admin/login", [App\Http\Controllers\AdminController::class, "showLoginForm"])
    ->name("adminlogin");

Route::get("/login", [AuthController::class, "login"])
    ->name("login");

Route::get("/reset-email", [AuthController::class, "emailsend"])
    ->name("emailsend");
   
Route::get("/reset-mail", [AuthController::class, "resetlink"])
    ->name("password.reset");
    
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
    
    Route:: get("admin/plan", [ZohoController::class, "plandb"])
    ->name("plandb");
    Route::get('admin/plans/create', [ZohoController::class, 'create'])
    ->name('plans.create');
    Route::post('plans', [ZohoController::class, 'storeplan'])
    ->name('plans.store');
Route:: get("admin/partner", [ZohoController::class, "cust"])
    ->name("cust");

Route:: get("/in", [AuthController::class, "index"])
    ->name("in");

Route::get('admin/plans', [ZohoController::class, "getAllPlans"])
    ->name('plans');
Route:: get("admin/support-ticket", [ZohoController::class, "supportticket"])
    ->name("Support.Ticket");

 Route::get('/customer/profile', [ZohoController::class, 'showCustomerDetails'])
     ->name('customer.details');


     Route::get('/customer/providerdata', [ProviderController::class, 'ProviderData'])
     ->name('customer.provider');     

     Route::get('/customer/companyinfo', [ CompanyinfoController::class, 'companyinfo'])
     ->name('customer.companyinfo');  
Route::put('/customers/{zohocust_id}/update-address', [ZohoController::class, 'addupdate'])->name('customers.addupdate');




    Route:: get("customers/allcustomer", [ZohoController::class, "customerdb"])
    ->name("customerdb");

Route::get('customers/allcustomers',[ZohoController::class, "getAllCustomers"])
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


Route::get('/customer/subscription/details',[ZohoController::class,'thankyousub']) ->name('thankyousub');
Route::get('/customer/upgrade/details',[ZohoController::class,'thanksup']) ->name('thanksup');


Route::get('/payments/{zoho_cust_id}', [ZohoController::class, 'editPayment'])->name('payments.edit');

Route::get('admin/subscription',[Zohocontroller::class,'showsubscription'])->name('subdata');
Route::get('/admin/invoices',[Zohocontroller::class,'showinvoice'])->name('invdata');

Route::get('/thankyou', [ZohoController::class, 'retrieveHostedPage'])->name('thankyou');
Route::get('/thanks', [ZohoController::class, 'retrieveRetHostedPage'])->name('thanks');
Route::get('/subdown', [ZohoController::class, 'retrievedowntHostedPage'])->name('subdown');

Route::get('/customer/subscriptions', [ZohoController::class, 'showCustomerSubscriptions'])->name('customer.subscriptions');
Route::get('/customer/invoices', [ZohoController::class, 'showCustomerInvoices'])->name('customer.invoices');
Route::get('/customer/creditnotes', [ZohoController::class, 'showCustomerCredits'])->name('customer.credites');
Route::get('/customer/support-ticket', [ZohoController::class, 'showCustomerSupport'])->name('customer.support');


Route::get('/customers/{zohocust_id}/edit', [ZohoController::class, 'edit'])->name('customers.edit');

Route::put('/customers/{id}', [ZohoController::class, 'update'])->name('customers.update');

Route::get('/profile',[ZohoController::class,'profile'])->name('profile');
Route::get('/zoho/payment/callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');
Route::get('/zoho/payment', [ZohoController::class, 'callback'])->name('zoho.call');

Route::post('/upgrade-subscription', [ZohoController::class, 'upgrade'])->name('upgrade.subscription');

Route::post('/downgrade-subscription', [ZohoController::class, 'downgradesub'])->name('downgrade.subscription');

Route::get('/upgrade-custsubscription', [ZohoController::class, 'custsupgrade'])->name('custsupgrade');

Route::get('/invoices/filter', [ZohoController::class, 'filterInvoices'])->name('invoices.filter');
Route::get('/creditnotes/filter', [ZohoController::class, 'filtercredits'])->name('creditnotes.filter'); // Filter credit notes

Route::post('/tickets/store', [ZohoController::class, 'ticketstore'])->name('tickets.store');
Route::get('/support', [ZohoController::class, 'showCustomerSupport'])->name('show.support');
Route::post('/downgrade-plan', [ZohoController::class, 'downgrade'])->name('downgrade_plan');
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route ::get('/subscriptions/filter', [ZohoController::class, 'filterSubscriptions'])->name('subscriptions.filter');
Route ::get('/Invoice/filter', [ZohoController::class, 'filteradInvoices'])->name('invoices.adfilter');
Route ::get('/support/ticket/filter', [ZohoController::class, 'supportticketfilter'])->name('support.adfilter');



Route ::get('/pdt/{creditnote_id}', [ZohoController::class, 'pdfdownload'])->name('pdf.download');

Route::get('/test-mail', function () {
    \Mail::raw('This is a test email', function ($message) {
        $message->to('your_test_email@example.com')
            ->subject('Test Email');
    });
    return 'Test email sent!';
});

