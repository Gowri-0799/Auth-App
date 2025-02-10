<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CompanyinfoController;
use App\Http\Controllers\AffiliateController;
use Illuminate\Support\Facades\Route;

// Redirect unauthenticated users to login
Route::get('/', function () { return redirect()->route('login'); })->name('home');
Route::get('/admin', function () { return redirect()->route('adminlogin'); })->name('adminhome');

// Public Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('adminlogin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/reset-email', [AuthController::class, 'sendPasswordResetEmail'])->name('password.reset.email');
Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
Route::get('admin/password/reset', [AdminController::class, 'adshowLinkRequestForm'])->name('admin.password.request');
Route::post('admin/password/reset-email', [AdminController::class, 'adsendPasswordResetEmail'])->name('adpassword.reset.email');
Route::post('admin/password/update', [AdminController::class, 'updateAdminPassword'])->name('admin.password.update');

// Middleware  Routes for Normal Users
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/usagereports', [ZohoController::class, 'showChart'])->name('usagereports');
    
    Route::get('/customer/plans', [ZohoController::class, 'showplan'])->name('showplan');

    Route::get('/customer/invoices', [ZohoController::class, 'showCustomerInvoices'])->name('customer.invoices');

    Route::get('/customer/creditnotes', [ZohoController::class, 'showCustomerCredits'])->name('customer.credites');
    Route::get('/customer/support-ticket', [ZohoController::class, 'showCustomerSupport'])->name('customer.support');

    Route::get('/customer/subscriptions', [ZohoController::class, 'showCustomerSubscriptions'])->name('customer.subscriptions');
    Route::get('/customer/profile', [ZohoController::class, 'showCustomerDetails'])->name('customer.details');
    Route::get('/customer/providerdata', [ZohoController::class, 'ProviderData'])->name('customer.provider');
    Route::get('/customer/companyinfo', [ZohoController::class, 'companyinfo'])->name('customer.companyinfo');
    Route::put('/customers/{zohocust_id}/update-address', [ZohoController::class, 'addupdate'])->name('customers.addupdate');
    Route::post('/profile/password/update', [ZohoController::class, 'updatePasswordinprofile'])->name('profile.password.update');
    
    Route::get('/verify-otp', [AuthController::class, 'otppage'])->name('otppage');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
});

// Middleware  Routes for Admins
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/usagereports', [ZohoController::class, 'adminshowChart'])->name('adminusagereports');
    Route::get('/admin/customers', [ZohoController::class, 'getAllCustomers'])->name('customers');
    Route::get('/admin/invoices', [ZohoController::class, 'showinvoice'])->name('invdata');
    Route::get('/admin/subscription', [ZohoController::class, 'showsubscription'])->name('subdata');
    Route::get('/admin/plans', [ZohoController::class, 'getAllPlans'])->name('plans');
    Route::get('/admin/support-ticket', [ZohoController::class, 'supportticket'])->name('Support.Ticket');
    Route::get('/admin/terms-log', [ZohoController::class, 'termslog'])->name('terms.log');

    Route::get('/admin/verify-otp', [AdminController::class, 'adminotppage'])->name('adminotppage');
    Route::post('/admin/verify-otp', [AdminController::class, 'adminverifyOtp'])->name('adminverify.otp');
    Route::post('/admin/resend-otp', [AdminController::class, 'adminresendOtp'])->name('adminresend.otp');
});

// Miscellaneous Routes
Route::post('/upgrade-subscription', [ZohoController::class, 'upgrade'])->name('upgrade.subscription');
Route::get('/preview/subscription', [ZohoController::class, 'showsubscribePreview'])->name('preview.subscribe');
Route::post('/subscribe/{planId}', [ZohoController::class, 'subscribe'])->name('customer.subscribe');

Route::post('/subscriptions/upgrade', [ZohoController::class, 'upgradelink'])->name('upgradelink');
Route::post('/downgrade-subscription', [ZohoController::class, 'downgradesub'])->name('downgrade.subscription');
Route::get('/upgrade-custsubscription', [ZohoController::class, 'custsupgrade'])->name('custsupgrade');


Route::post('/upgrade/subscription', [ZohoController::class, 'processUpgrade'])->name('upgrade.subscription.detail');

Route::post('/store-terms', [ZohoController::class, 'storeTerms'])->name('storeTerms');

Route::post('/refund-payment', [ZohoController::class, 'refundPayment'])->name('refund.payment');
Route::post('/invite-user', [ZohoController::class, 'inviteUser'])->name('invite-user');
Route::post('/cancel-subscription', [ZohoController::class, 'cancelSubscription'])->name('support.Subscription');

Route::post('/custom/enterprise', [ZohoController::class, 'customenterprise'])->name('custom.enterprise');
Route::get('/support', [ZohoController::class, 'showCustomerSupport'])->name('show.support');
Route::post('/tickets/store', [ZohoController::class, 'ticketstore'])->name('tickets.store');
Route::get('/creditnotes/filter', [ZohoController::class, 'filtercredits'])->name('creditnotes.filter');
Route::get('/invoices/filter', [ZohoController::class, 'filterInvoices'])->name('invoices.filter');
Route::post('/downgrade-plan', [ZohoController::class, 'downgrade'])->name('downgrade_plan');
Route::get('/upgrade/preview', [ZohoController::class, 'showUpgradePreview'])->name('upgrade.preview');
Route::post('/addon-preview', [ZohoController::class, 'showAddonPreview'])->name('addon.preview');
Route::post('/cancel-subscription/alert', [ZohoController::class, 'Cancellation'])->name('cancel.subscription');
Route::get('/usagereports/download', [ZohoController::class, 'downloadCsv'])->name('usagereports.download');
Route::post('/company-info/update', [ZohoController::class, 'updatecompanyinfo'])->name('company-info.update');
Route::get('/provider-info', [ZohoController::class, 'ProviderDatafilter'])->name('provider.info');

// Other Routes
Route::post('/upload-csv', [ZohoController::class, 'uploadCsv'])->name('provider-data.upload');
Route::post('admin/upload-csv', [ZohoController::class, 'aduploadCsv'])->name('admin.provider-data.upload');
Route::post('/resend-invite', [ZohoController::class, 'resendInvite'])->name('resend.invite');
Route::get('/customers/filter', [ZohoController::class, 'customfilter'])->name('customer.filter'); 

Route::get('/terms-log', [ZohoController::class, 'filterTermsLog'])->name('term.adfilter');
Route::get('/zoho/payment/callback', [ZohoController::class, 'handleZohoCallback'])->name('zoho.callback');
Route::get('/zoho/payment', [ZohoController::class, 'callback'])->name('zoho.call');

Route::put('/admin/{zohocust_id}/update-plans', [ZohoController::class, 'updatePlans'])
    ->name('partner.updatePlans');
    Route::get('admin/plan', [ZohoController::class, 'plandb']) ->name("plandb");
    Route::get("/reset-email", [AuthController::class, "emailsend"])
    ->name("emailsend");
    Route::get("admin/reset-email", [AdminController::class, "ademailsend"])
    ->name("ademailsend");
    Route::get("/reset-mail", [AuthController::class, "resetlink"])
    ->name("password.reset");
    Route::get("admin/reset-mail", [AdminController::class, "adresetlink"])
    ->name("adpassword.reset");
    Route:: get("/plantest", [ZohoController::class, "plantest"])
    ->name("plantest");
    Route::get('/plans/{id}/view', [ZohoController::class, 'view'])->name('plans.view');
    Route::post('/update-features', [ZohoController::class, 'updateFeatures'])->name('update.features');
    Route::get('admin/plans/create', [ZohoController::class, 'create'])
    ->name('plans.create');
    Route::post('plans', [ZohoController::class, 'storeplan'])
    ->name('plans.store');
    Route:: get("admin/partner", [ZohoController::class, "cust"])
    ->name("cust");
    Route:: get("/in", [AuthController::class, "index"])
    ->name("in");
    Route::post('profile/admin/password', [AdminController::class, 'adminupdatePassword'])->name('adpro.password.update');
    Route::get('/admin/profile', [AdminController::class, 'adminprofile'])
    ->name('admin.profile');
    Route:: get("admin/allcustomer", [ZohoController::class, "customerdb"])
    ->name("customerdb");

    Route::get('/tokens', function () {
        return view('generateaccesstoken'); })->name('tokens');
        Route::post('/generate-access-token', [ZohoController::class, 'generateAccessTokenAndStore'])
        ->name('generate.access.token');
    
Route::get('/custdetail', [AuthController::class, 'customerdetail'])
->name('custdetail'); 
Route::post('/customerdetail', [ZohoController::class, 'storepartner'])->name('customers.store');

Route::get('admin/customer',[ZohoController::class,'display']) ->name('cust.display');


Route::post('/addon', [ZohoController::class, 'addons'])->name('addon');

Route::get('/customer/subscription/details',[ZohoController::class,'thankyousub']) ->name('thankyousub');
Route::get('/customer/upgrade/details',[ZohoController::class,'thanksup']) ->name('thanksup');
Route::get('/customer/subscription/addon',[ZohoController::class,'thanksadd']) ->name('thanksadd');


Route::get('/payments/{zoho_cust_id}', [ZohoController::class, 'editPayment'])->name('payments.edit');
Route::get('/thankyou', [ZohoController::class, 'retrieveHostedPage'])->name('thankyou');
Route::get('/thanks', [ZohoController::class, 'retrieveRetHostedPage'])->name('thanks');

Route::get('/addonthanks', [ZohoController::class, 'retrieveaddonHostedPage'])->name('addonthanks');

Route::get('/subdown', [ZohoController::class, 'retrievedowntHostedPage'])->name('subdown');

Route::get('/customers/{zohocust_id}/edit', [ZohoController::class, 'edit'])->name('customers.edit');
Route::put('/customers/{id}', [ZohoController::class, 'update'])->name('customers.update');

Route::get('/profile',[ZohoController::class,'profile'])->name('profile');

Route ::get('subscriptions/filter', [ZohoController::class, 'filterSubscriptions'])->name('subscriptions.filter');

Route ::get('/Invoice/filter', [ZohoController::class, 'filteradInvoices'])->name('invoices.adfilter');
Route ::get('/support/ticket/filter', [ZohoController::class, 'supportticketfilter'])->name('support.adfilter');

Route ::get('nav/subscriptions/filter', [ZohoController::class, 'filterSubscriptionsnav'])->name('nav.subscriptions.filter');
Route::get('nav/invoices/filter', [ZohoController::class, 'filterInvoicesnav'])->name('nav.invoice.filter');
Route::get('nav/creditnote/filter', [ZohoController::class, 'filtercreditnav'])->name('nav.creditnote.filter');
Route::get('nav/providerdata/filter', [ZohoController::class, 'filterProviderDatanav'])->name('nav.provider.filter');
Route::get('nav/clicks/filter', [ZohoController::class, 'filterclicksnav'])->name('nav.clicks.filter');
Route::get('nav/refunds/filter', [ZohoController::class, 'filterrefundnav'])->name('nav.refund.filter');

Route ::get('/pdt/{creditnote_id}', [ZohoController::class, 'pdfdownload'])->name('pdf.download');

Route::post('admin/company-info/update', [ZohoController::class, 'updateCompanyInfoForPartner'])->name('admin.company-info.update');
Route::get('/customers/{zohocust_id}/view', [ZohoController::class, 'show'])->name('customers.show');

Route::get('admin/affiliates', [AffiliateController::class, 'affiliate'])->name('affiliates.index');
Route::post('/affiliates', [AffiliateController::class, 'affiliatestore'])->name('affiliates.store');

Route::get('admin/admins', [AdminController::class, 'adminview'])->name('admin.index');

Route::post('/revoke-ticket', [ZohoController::class, 'revokeTicket'])->name('revoke_ticket');

Route::get('/admin/create', [AdminController::class, 'addadmin'])->name('admin.invite');
Route::post('/invite-admin', [AdminController::class, 'store'])->name('admin.store');

Route::get('admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('admin/{id}', [AdminController::class, 'update'])->name('admin.update');

Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

Route::post('admin/subscribe', [ZohoController::class, 'subscribelink'])->name('subscribelink');

Route::post('/customers/{id}/mark-inactive', [ZohoController::class, 'markAsInactive'])
     ->name('customer.markInactive');
Route::post('/customers/{id}/mark-active', [ZohoController::class, 'markAsActive'])
     ->name('customer.markActive');

     Route::put('/users/update', [ZohoController::class, 'updateinviteuser'])->name('users.update');

     Route::get('/admin/dashboard', [ZohoController::class, 'plandb'])->name('admin.dashboard');