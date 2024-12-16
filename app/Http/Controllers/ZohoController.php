<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Addon;
use App\Models\Term;
use App\Models\CompanyInfo;
use App\Models\ProviderData;
use App\Models\Affiliate;
use App\Models\PartnerUser;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Route;
use App\Models\Partner;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\ZohoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Creditnote;
use Carbon\Carbon;
use App\Models\Support;
use Illuminate\Support\Facades\Storage;
use App\Mail\SubscriptionDowngrade;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerInvitation;
use App\Mail\SubscriptionEmail;
use App\Mail\UpgradeEmail;
use Illuminate\Support\Str;
use App\Models\Feature;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ZohoController extends Controller
{
    protected $zohoService;
    
    protected $plan;
    protected $partner;
    protected $subscription;
    protected $invoice;
    protected $payment;
    protected $Creditnote;
    protected $support;
    protected $addon;
    protected $term;
    protected $companyinfo;
    protected $providerdata;
    protected $affiliate;
    protected $partneruser;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
        $this->plan = new Plan();
        $this->partner = new Partner();
        $this->subscription = new Subscription();
        $this->invoice =new Invoice();
        $this->payment =new Payment();
        $this->creditnote =new Creditnote();
        $this->support=new Support();
        $this->addon=new Addon();
        $this->term=new Term();
        $this->companyinfo=new CompanyInfo();
        $this->providerdata=new ProviderData();
        $this->affiliate=new Affiliate();
        $this->partneruser= new PartnerUser();
    }

    public function getAllPlans()
    {
        $plans = $this->zohoService->getZohoPlans();
        $addons = $this->zohoService->getZohoAddons(); 

        if (isset($addons['addons'])) {
            Plan::truncate();
            $addonMap = [];
            foreach ($addons['addons'] as $addon) { 
                $addonCode = $addon['addon_code'] ?? null;
                if ($addonCode) {
                    
                    $addonPrice = null;
                    if (isset($addon['price_brackets']) && count($addon['price_brackets']) > 0) {
                        $addonPrice = $addon['price_brackets'][0]['price'] ?? null; 
                    }
    
                    $addonMap[$addonCode] = [
                        'addon_name' => $addon['name'] ?? null,
                        'addon_price' => $addonPrice
                    ];
                }
            }
    
            foreach ($plans['plans'] as $plan) {
                $this->plan = new Plan(); 
    
                $this->plan->plan_name = $plan['name'] ?? null; 
                $this->plan->plan_price = $plan['recurring_price'] ?? null; 
                $this->plan->plan_code = $plan['plan_code'] ?? null;
                $this->plan->plan_id = $plan['plan_id'] ?? null;

                $addonCode = $plan['addons'][0]['addon_code'] ?? null; 

                if ($addonCode && isset($addonMap[$addonCode])) {
                    $this->plan->addon_code = $addonCode;
                    $this->plan->addon_name = $addonMap[$addonCode]['addon_name']; 
                    $this->plan->addon_price = $addonMap[$addonCode]['addon_price'];
                } else {
                    $this->plan->addon_code = null; 
                    $this->plan->addon_name = null; 
                    $this->plan->addon_price = null; 
                }
                
                $this->plan->save(); 
            }
        }
    
        return redirect(route("plantest"));
    }


    function plantest()
    {
        $response['plans'] = $this->plan->all();
      
        return view("plan")->with($response);
    }
   
    public function plandb()
    {
        // Fetch all plans from the 'plans' table
        $plans = Plan::all();
    
        // Pass the plans to the view
        return view('plan', compact('plans'));
    }
    public function create()
    {
        // Return the view where the form is located
        return view('plans.create');
    }

    public function storeplan(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'plan_price' => 'required|numeric',
            'plan_code' => 'required|string|max:255',
        ]);

        $existingPlan= Plan::where('plan_name',  $validated['plan_name'])
        ->where('plan_code',  $validated['plan_code'])
        ->first();
        
        if (!$existingPlan) {
            
            $plan = Plan::create([
                
                'plan_name' => $validated['plan_name'],
                'plan_price' => $validated['plan_price'],
                'plan_code' => $validated['plan_name'],
            ]);

        $zohoPlanId = $this->createPlanInZoho($plan);
      
        $plan->plan_id = $zohoPlanId;
     
         $plan->save();
        }
        else{
            foreach ($validated as $key => $value) {
                if (!is_null($value)) {
                    $existingPlan->$key = $value;
                }
            }
        }
        return redirect(route("plantest"));
       
   
        
    }

    private function createPlanInZoho($plan)
    {
        
        $accessToken = $this->zohoService->getAccessToken();

        
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json',
            'organization_id' => config('services.zoho.zoho_org_id'),
        ])->post('https://www.zohoapis.com/billing/v1/plans', [
            'plan_code' => $plan->plan_code,
            'name' => $plan->plan_name,
            'recurring_price' => (float) $plan->plan_price, 
            'product_id' => '5437538000000088227', 
            'interval_unit' => 'months', 
            'interval' => 1, 
        ]);
        
      
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['plan']['plan_id']; 
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }

    public function customerdb()
    {
        $response['customers'] = \DB::table('partners')
            ->leftJoin('partner_users', 'partners.zohocust_id', '=', 'partner_users.zoho_cust_id')
            ->select('partners.*')
            ->whereNull('partner_users.zoho_cpid') 
            ->get();
         
        return view('cust')->with($response);
    }
    public function getAllCustomers()
    {
        $customers = $this->zohoService->getZohoCustomers();

        if (isset($customers['customers'])) {

            $defaultPassword = Hash::make('soxco123');
            foreach ($customers['customers'] as $customer) {

                $customerId = $customer['customer_id'];

                // Get detailed customer info
                $customerDetails = $this->zohoService->getCustomerDetails($customerId);

                if ($customerDetails['code'] == 0) {
                    $details = $customerDetails['customer'];

                    Partner::updateOrCreate(
                        ['zohocust_id' => $customerId],
                        ['customer_name' => $details['display_name'],
                            'customer_email' => $details['email'],
                            'first_name' => $details['first_name'],
                            'last_name' => $details['last_name'],
                            'company_name' => $details['company_name'],
                            'password' => $defaultPassword,
                            'billing_attention' => $details['billing_address']['attention'],
                            'billing_street' => $details['billing_address']['street'],
                            'billing_city' => $details['billing_address']['city'],
                            'billing_state' => $details['billing_address']['state'],
                            'billing_zip' => $details['billing_address']['zip'],
                            'billing_country' => $details['billing_address']['country'],
                            'billing_fax' => $details['billing_address']['fax'],
                            'shipping_attention' => $details['shipping_address']['attention'],
                            'shipping_street' => $details['shipping_address']['street'],
                            'shipping_city' => $details['shipping_address']['city'],
                            'shipping_state' => $details['shipping_address']['state'],
                            'shipping_zip' => $details['shipping_address']['zip'],
                            'shipping_country' => $details['shipping_address']['country'],
                            'shipping_fax' => $details['shipping_address']['fax'],
                            ]
                    );
                }
            }
        }

        return redirect(route("cust"));
    }

    public function showCustomerDetails()
    {
        $partnerUser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partnerUser) {
            return back()->withErrors('Partner User not found.');
        }
    
        $customer = Partner::where('zohocust_id', $partnerUser->zoho_cust_id)->first();
    
        if (!$customer) {
            return back()->withErrors('Partner not found.');
        }
    
        $payments = Payment::where('zoho_cust_id', $customer->zohocust_id)->get();
    
        $invitedUsers = PartnerUser::where('zoho_cust_id', $partnerUser->zoho_cust_id)
            ->where('id', '!=', $partnerUser->id)
            ->get();
    
        return view('profile', compact('customer','payments','partnerUser','invitedUsers'));
           
       
    }
    


    function cust()
    {
        $response['customers'] = $this->partner->all();
        return view("cust")->with($response);
    }

    public function showplan()
    {
        $partnerUser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partnerUser) {
            return back()->withErrors('Customer not found.');
        }
    
        $customer = Partner::where('zohocust_id', $partnerUser->zoho_cust_id)->first();
    
        if (!$customer) {
            return back()->withErrors('Customer record not found in the customers table.');
        }
    
        $subscriptions = Subscription::where('zoho_cust_id', $partnerUser->zoho_cust_id)->get();
        $companyInfo = CompanyInfo::where('zoho_cust_id', $partnerUser->zoho_cust_id)->first();
        $plans = Plan::orderBy('plan_price', 'asc')->get();
        $providerData = ProviderData::where('zoho_cust_id', $partnerUser->zoho_cust_id)->first();
      
        $planFeatures = Feature::whereIn('plan_code', $plans->pluck('plan_code'))->get()->keyBy('plan_code');
      
        foreach ($planFeatures as $key => $feature) {
            if (is_string($feature->features_json)) {
                $feature->features_json = json_decode($feature->features_json, true);
            }
        }
    
        $firstLogin = $customer->first_login;
    
        return view('sub', compact('subscriptions', 'plans', 'firstLogin', 'companyInfo', 'providerData', 'planFeatures'));
    }
    
    
    function showsubscription()
    {
        $subscriptions = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->join('partners', 'subscriptions.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'subscriptions.subscription_id',
            'subscriptions.subscription_number',
            'partners.company_name',
            'plans.plan_name',
            'plans.plan_price',
            'subscriptions.start_date',
            'subscriptions.next_billing_at',
            'subscriptions.status'
        )
        ->get();

       
    return view('subscription', compact('subscriptions'));
    }
    
    
    public function showinvoice()
    {
      
        $invoices = DB::table('invoices')
            ->join('partners', 'invoices.zoho_cust_id', '=', 'partners.zohocust_id')
            ->select(
                'invoices.*',
                'partners.company_name',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) AS plan_name"), 
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].price')) AS plan_price"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_details, '$[0].payment_mode')) AS payment_mode")
            )
            ->get(); 
    
        return view('invoice', compact('invoices'));
    }
    
    public function filteradInvoices(Request $request)
    {
       
        $search = $request->input('search');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $perPage = $request->input('show', 10); 
    
        $query = DB::table('invoices')
            ->join('partners', 'invoices.zoho_cust_id', '=', 'partners.zohocust_id')
            ->select(
                'invoices.*',
                'partners.company_name',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) AS plan_name"), 
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].price')) AS plan_price"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_details, '$[0].payment_mode')) AS payment_mode")
            );

        if ($startDate) {
            $query->whereDate('invoice_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('invoice_date', '<=', $endDate);
        }
    
        // Apply search filter on the company name or invoice number
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('invoices.invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) LIKE ?", ["%{$search}%"]); // Searching plan_name from JSON
            });
        }
    
        // Paginate the results
        $invoices = $query->paginate($perPage);
    
        // Pass the filtered data back to the view
        return view('invoice', compact('invoices', 'search', 'startDate', 'endDate'));
    }
    


    public function generateAccessTokenAndStore(){
        $accessToken = $this->zohoService->getAccessToken();
       
        return back()->with('accessToken', $accessToken);
    }

    public function display(){
        $affiliates = Affiliate::all();
       
    return view("custdetail", compact('affiliates'));
    }

    public function storepartner(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'customer_email' => 'required|email|unique:partner_users,email', // Ensure uniqueness only in partner_users
            'company_name' => 'required|string',
            'billing_street' => 'nullable|string',
            'billing_city' => 'nullable|string',
            'billing_state' => 'nullable|string',
            'billing_country' => 'nullable|string',
            'billing_zip' => 'nullable|string',
            'affiliate_ids' => 'required|array',
            'affiliate_ids.*' => 'exists:affiliates,id',
        ], [
            'customer_email.unique' => 'The email ID already exists.',
            'affiliate_ids.required' => 'Please select the affiliate ID.',
        ]);
    
        $fullName = trim($validatedData['first_name'] . ' ' . $validatedData['last_name']);
    
        // Check if customer_name already exists in customers table
        $exists = Partner::where('customer_name', $fullName)->exists();
        if ($exists) {
            return redirect()->back()->withErrors([
                'name_combination' => 'The combination of first name and last name already exists.',
            ])->withInput();
        }
    
        $defaultPassword = Str::random(16);
        try {
            $customerData = [
                'customer_name' => $fullName,
                'first_name' =>$validatedData['first_name'],
                'last_name' =>$validatedData['last_name'],
                'email' => $validatedData['customer_email'],
                'company_name' => $validatedData['company_name'],
                'billing_address' => $validatedData['billing_street'],
                'billing_city' => $validatedData['billing_city'],
                'billing_state' => $validatedData['billing_state'],
                'billing_country' => $validatedData['billing_country'],
                'billing_zip' => $validatedData['billing_zip'],
                'shipping_address' => $validatedData['billing_street'],
                'shipping_city' => $validatedData['billing_city'],
                'shipping_state' => $validatedData['billing_state'],
                'shipping_country' => $validatedData['billing_country'],
                'shipping_zip' => $validatedData['billing_zip'],
            ];
    
            // Create customer in Zoho
            $zohoResponse = $this->createCustomerInZoho($customerData);
            if (!isset($zohoResponse['customer']['customer_id'])) {
                throw new \Exception('Failed to create a customer in Zoho. No customer ID returned.');
            }
    
            $zohoCustomerId = $zohoResponse['customer']['customer_id'];
    
            // Save to customers table
            $customer = Partner::create([
                'customer_name' => $fullName,
                'company_name' => $validatedData['company_name'],
                'status'=>'invited',
                'billing_attention' => $fullName,
                'billing_street' => $validatedData['billing_street'],
                'billing_city' => $validatedData['billing_city'],
                'billing_state' => $validatedData['billing_state'],
                'billing_country' => $validatedData['billing_country'],
                'billing_zip' => $validatedData['billing_zip'],
                'shipping_attention' => $fullName,
                'shipping_street' => $validatedData['billing_street'],
                'shipping_city' => $validatedData['billing_city'],
                'shipping_state' => $validatedData['billing_state'],
                'shipping_country' => $validatedData['billing_country'],
                'shipping_zip' => $validatedData['billing_zip'],
                'zohocust_id' => $zohoCustomerId,
            ]);
    
            $partnerUser = PartnerUser::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['customer_email'],
                'password' => Hash::make($defaultPassword),
                'zoho_cust_id' => $zohoCustomerId,
               
            ]);
    
            if ($request->has('affiliate_ids')) {
                $affiliateIds = $request->input('affiliate_ids');
                foreach ($affiliateIds as $affiliateId) {
                    \DB::table('partner_affiliates')->insert([
                        'partner_id' => $zohoCustomerId,
                        'affiliate_id' => $affiliateId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } 
    
            $loginUrl = route('login');
            try {
                Mail::to($partnerUser->email)->send(new CustomerInvitation($partnerUser, $defaultPassword, $loginUrl,$customer));
            } catch (\Exception $e) {
                \Log::error('Failed to send email: ' . $e->getMessage());
                return redirect()->back()->withErrors('Partner created successfully; but unable to send email.')->withInput();
            }
    
            return redirect(route('cust'))->with('success', 'Customer and partner user added successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to create a partner: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to create a partner.')->withInput();
        }
    }
    

    private function createCustomerInZoho($customer)
    {
        $accessToken = $this->zohoService->getAccessToken();
  
        \Log::info('Customer data:', ['customer' => $customer]);
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken
        ])->post('https://www.zohoapis.com/billing/v1/customers', [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'display_name' => $customer['customer_name'],
            'first_name' => $customer['first_name'],
            'last_name' => $customer['last_name'],
            'email' => $customer['email'],
            'company_name' => $customer['company_name'],
            'billing_address' => [
                'attention' => $customer['customer_name'],
                'street' => $customer['billing_address'],
                'city' => $customer['billing_city'],
                'state' => $customer['billing_state'],
                'country' => $customer['billing_country'],
                'zip' => $customer['billing_zip'],
            ],
            'shipping_address' => [
                'attention' => $customer['customer_name'],
                'street' => $customer['shipping_address'],
                'city' => $customer['shipping_city'],
                'state' => $customer['shipping_state'],
                'country' => $customer['shipping_country'],
                'zip' => $customer['shipping_zip'],
            ]
        ]);
    
        \Log::info('Zoho API Response:', ['response' => $response->body()]);
      
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData; // Return the entire response for further use
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }

    public function subscribe(Request $request,$planId)
{

    $validated = $request->validate([
        'zoho_cust_id' => 'required|string',
        'plan_code'=>'required|string',
        'zoho_cpid' => 'nullable|string',
        'plan_name' => 'required|string',
        'amount' => 'required|numeric',
        'consent' => 'required|boolean',  
    ]);

    Term::create([
        'zoho_cust_id' => $validated['zoho_cust_id'],
        'zoho_cpid' => $validated['zoho_cpid'] ?? null,
        'subscription_number' => null,
        'ip_address' => $request->ip(),  
        'browser_agent' => $request->header('User-Agent'), 
        'consent' => $validated['consent'],  
        'plan_name' => $validated['plan_name'],
        'amount' => $validated['amount'],
    ]);

   
        $accessToken = $this->zohoService->getAccessToken();

        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 

        if (!$customer) {
            return back()->withErrors('Customer not found.');
        }


        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json' // Ensure the Content-Type is JSON
        ])->post(config('services.zoho.zoho_new_subscription'), [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'customer_id'  => $customer->zohocust_id, // Zoho customer ID
            'customer' => [
                'display_name' => $customer->customer_name,
                'email'        => $partneruser->email,
            ],
            'plan' => [
                    'plan_code' => $planId, // Plan code from the form
            ],
            'redirect_url' => url('thankyou')
        ]);

        if ($response->successful()) {
            $hostedPageData = $response->json();
            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
            $hostedPageId = $hostedPageData['hostedpage']['hostedpage_id'];

            // Session::put('subscribed_plan_code', $planId);
            Session::put('hostedpage_id', $hostedPageId); // Save hostedpage_id for later retrieval
            //$this->retrieveHostedPage();
            return redirect()->to($hostedPageUrl);
            //return redirect()->route('thankyou')->with('success', 'Subscription successfully completed!');

        } else {
            return back()->withErrors('Error creating subscription: ' . $response->body());
        }
    }

    public function retrieveHostedPage()
    {
        $accessToken = $this->zohoService->getAccessToken();
        $hostedPageId = Session::get('hostedpage_id'); // Retrieve the hostedpage_id from the session

        if (!$hostedPageId) {
            return back()->withErrors('Hosted page ID is missing.');
        }

       
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json'
        ])->get("https://www.zohoapis.com/billing/v1/hostedpages/{$hostedPageId}");
       
        if ($response->successful()) {
            $hostedPageData = $response->json();
           
        // dd($hostedPageData);
            //if (isset($hostedPageData['hostedpage']['data']['invoice']['payments'])) {
                $this->storeSubscriptionData($hostedPageData); // Pass the JSON data to store it
                return redirect()->route('thankyousub');
            /*} else {
                return back()->withErrors('Payment method is missing in the response.');
            }*/
        } else {
            return back()->withErrors('Error retrieving hosted page data: ' . $response->body());
        }
    }


    public function retrieveRetHostedPage()
    {
        $accessToken = $this->zohoService->getAccessToken();
        $hostedPageId = Session::get('hostedpage_id'); // Retrieve the hostedpage_id from the session

        if (!$hostedPageId) {
            return back()->withErrors('Hosted page ID is missing.');
        }

       
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json'
        ])->get("https://www.zohoapis.com/billing/v1/hostedpages/{$hostedPageId}");
       
        if ($response->successful()) {
            $hostedPageData = $response->json();
         
                $this->upgradeSubscriptionData($hostedPageData); 
                return redirect()->route('thanksup');
              
        } else {
            return back()->withErrors('Error retrieving hosted page data: ' . $response->body());
        }
    }
    
    public function storeSubscriptionData($data)
    {
        // Step 1: Extract data from the Zoho API response
        $subscriptionData = $data['data']['subscription'];   
        $invoiceData = $data['data']['invoice'];       
         $paymentMethodData = $invoiceData['payments'] ?? []; 
         $existingSubscription = Subscription::where('subscription_id', $subscriptionData['subscription_id'])->first();
         $cardData = $subscriptionData['card'];
         $paymentMethodId = $cardData['card_id'] ?? ($paymentMethodData['payment_method_id'] ?? null);
        //  dd($paymentMethodId);
        if (!$existingSubscription) {
             $subscription = Subscription::create([
            'subscription_id' => $subscriptionData['subscription_id'],
            'subscription_number' => $subscriptionData['subscription_number'],
            'plan_id' => $subscriptionData['plan']['plan_id'],
            'invoice_id' => $invoiceData['invoice_id'],
            'payment_method_id' => $paymentMethodId,
            'next_billing_at' => $subscriptionData['next_billing_at'],
            'start_date' => $subscriptionData['start_date'],
            'zoho_cust_id' => $subscriptionData['customer_id'],
            'status' => $subscriptionData['status'], 
        ]);
    } else {
        $existingSubscription->updateOrInsert([
            'subscription_number' => $subscriptionData['subscription_number'],
            'plan_id' => $subscriptionData['plan']['plan_id'],
            'invoice_id' => $invoiceData['invoice_id'],
            'payment_method_id' => $paymentMethodId,
            'next_billing_at' => $subscriptionData['next_billing_at'],
            'start_date' => $subscriptionData['start_date'],
            'zoho_cust_id' => $subscriptionData['customer_id'],
            'status' => $subscriptionData['status'], 
        ]);
    }
// Check if an invoice record with the same invoice_id already exists
$existingInvoice = Invoice::where('invoice_id', $invoiceData['invoice_id'])->first();
$invoiceItems = json_encode($invoiceData['invoice_items'] ?? []);
$paymentItems = json_encode($invoiceData['payments'] ?? []);
if (!$existingInvoice) {
    // Create a new invoice record if it doesn't already exist
    Invoice::create([
        'invoice_id' => $invoiceData['invoice_id'],
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'], // Link to subscription
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'payment_details' => $paymentItems,
        'status' => $invoiceData['status'],
    ]);
} else {
    // Optionally, update the existing record if needed
    $existingInvoice->updateOrInsert([
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'],
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method_id' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'status' => $invoiceData['status'],
    ]);
}
        // Step 4: Store payment method data in the 'payments' table (only if payment method exists)
        if (!empty($paymentMethodData) && isset($subscriptionData['card'])) {
            $cardData = $subscriptionData['card'];
            
            // Check if 'payments' exists in the 'invoice' section and is not empty
            $invoicePayments = $invoiceData['payments'] ?? [];
         
            if (!empty($invoicePayments)) {
                // Extract payment mode and amount from the first payment record (assuming single payment)
                $paymentDetails = $invoicePayments[0]; 
        
                // Check if a payment record with the same payment_method_id already exists
                $existingPayment = Payment::where('payment_method_id', $cardData['card_id'])->first();
        
                if (!$existingPayment) {
                    // Create a new payment record if it doesn't already exist
                    Payment::create([
                        'payment_method_id' => $cardData['card_id'] ?? null,// Use null if card_id not present
                        'type' => $cardData['card_type'] ?? null, // Correct field name
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  // Extract payment mode from payments array
                        'amount' => $paymentDetails['amount'] ?? null,              // Extract amount from payments array
                        'invoice_id' => $invoiceData['invoice_id'],  
                        'payment_id' =>$paymentDetails['payment_id'],
 
                    ]);
                } else {
                    // Optionally, update the existing record if needed
                    $existingPayment->updateOrInsert([
                        'payment_method_id' => $paymentDetails['payment_id'] ?? null,
                        'type' => $cardData['card_type'] ?? null,
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  // Extract payment mode from payments array
                        'amount' => $paymentDetails['amount'] ?? null,              // Extract amount from payments array
                        'invoice_id' => $invoiceData['invoice_id'],    
                         'payment_id' =>$paymentDetails['payment_id']
                    ]);
                }
            }
        }

        // Return success message or redirect as needed
        return redirect()->route('thankyousub')->with('success', 'Subscription successfully completed!');
    }
    public function thankyousub() {
        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();
    
        $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
        
        if (!$subscriptions) {
            return back()->withErrors('Subscription not found.');
        }
    
        // Fetch the plan associated with the subscription
        $plans = Plan::where('plan_id', $subscriptions->plan_id)->first();
    
        // Fetch invoices specifically associated with the subscription
        $invoice = Invoice::where('zoho_cust_id', $customer->zohocust_id)
                          ->where('subscription_id', $subscriptions->subscription_id) // Ensure there's a subscription_id column in your Invoice table
                          ->first();
    
        return view('thankyou', compact('subscriptions', 'plans', 'invoice'));
    }
    public function thankyou()
    {
        return view('thankyou');
    }

    public function thanks()
    {
        return view('thanks');
    }
     
    public function edit($zohocust_id)
    {
        // Find the customer by customer_email or fail if not found
        $customer = Partner::where('zohocust_id', $zohocust_id)->firstOrFail();
    
        // Return the edit view and pass the customer object
        return view('edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Find the customer by ID or fail if not found
        $customer = Partner::findOrFail($id);
    
        // Validate the request data
        $validatedData = $request->validate([
            
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'company_name'=>'required',
            'billing_attention' => 'nullable|string',
            'billing_street' => 'nullable|string',
            'billing_city' => 'nullable|string',
            'billing_state' => 'nullable|string',
            'billing_country' => 'nullable|string',
            'billing_zip' => 'nullable|string',
        ]);
        $fullName = trim($validatedData['first_name'] . ' ' . $validatedData['last_name']);
        // Update only the fields that are provided, keeping the existing data for other fields
        $customer->update([
            'customer_name' =>  $fullName ,
            'first_name' => $validatedData['first_name'] ?? $customer->first_name,
            'last_name' => $validatedData['last_name'] ?? $customer->last_name,
            'company_name' => $validatedData['company_name'] ?? $customer->company_name,
            'shipping_attention' =>  $fullName ?? $customer->customer_name,
            'shipping_street' => $validatedData['billing_street'] ?? $customer->billing_street,
            'shipping_city' => $validatedData['billing_city'] ?? $customer->billing_city,
            'shipping_state' => $validatedData['billing_state'] ?? $customer->billing_state,
            'shipping_country' => $validatedData['billing_country'] ?? $customer->billing_country,
            'shipping_zip' => $validatedData['billing_zip'] ?? $customer->billing_zip,
            'billing_attention' => $fullName  ?? $customer->billing_attention,
            'billing_street' => $validatedData['billing_street'] ?? $customer->billing_street,
            'billing_city' => $validatedData['billing_city'] ?? $customer->billing_city,
            'billing_state' => $validatedData['billing_state'] ?? $customer->billing_state,
            'billing_country' => $validatedData['billing_country'] ?? $customer->billing_country,
            'billing_zip' => $validatedData['billing_zip'] ?? $customer->billing_zip,
        ]);
    
        $zohoResponse = $this->updateCustomerInZoho($customer); 
        $customer->save();

        // Redirect back to the customers list with a success message
        return redirect()->route('cust')->with('success', 'Customer updated successfully!');
    }
    
    private function updateCustomerInZoho($customer)
{
    // Get Zoho access token
    $accessToken = $this->zohoService->getAccessToken();

    // Check if the customer has a Zoho customer ID
    if (!$customer->zohocust_id) {
        throw new \Exception('Zoho customer ID is missing for this customer.');
    }

    // Call Zoho API to update the customer
    $response = Http::withHeaders([
        'Authorization' => 'Zoho-oauthtoken ' . $accessToken
    ])->put('https://www.zohoapis.com/billing/v1/customers/' . $customer->zohocust_id, [
        'organization_id' => config('services.zoho.zoho_org_id'),
        'display_name' => $customer->customer_name,
        'first_name' => $customer->first_name,
        'last_name' => $customer->last_name,
        'email' => $customer->customer_email,
        'company_name'=>$customer->company_name,
        'billing_address' => [
            'attention' => $customer->billing_attention,
            'street' => $customer->billing_street,
            'city' => $customer->billing_city,
            'state' => $customer->billing_state,
            'country' => $customer->billing_country,
            'zip' => $customer->billing_zip,
        ],
        'shipping_address' => [
            'attention' => $customer->shipping_attention,
            'street' => $customer->shipping_street,
            'city' => $customer->shipping_city,
            'state' => $customer->shipping_state,
            'country' => $customer->shipping_country,
            'zip' => $customer->shipping_zip,
        ],
    ]);

    // Handle Zoho API response
    if ($response->successful()) {
        return true; // Customer updated successfully
    } else {
        throw new \Exception('Zoho API error: ' . $response->body());
    }
}

public function showCustomerSubscriptions()
{
    $customer = PartnerUser::where('email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Fetch subscriptions for the customer
    $subscriptions = Subscription::where('zoho_cust_id', $customer->zoho_cust_id)->first();
    
    // Check if subscriptions exist and fetch corresponding plan details
    $plans = null;
    if ($subscriptions) {
        $plans = Plan::where('plan_id', $subscriptions->plan_id)->first();
    }

    // Get downgrade plans if subscription exists
    $downgradePlans = $plans ? Plan::where('plan_price', '<', $plans->plan_price)->get() : [];
    $upgradePlans = $plans ? Plan::where('plan_price', '>', $plans->plan_price)->get() : [];
    return view('customerSubscriptions', compact('subscriptions', 'plans', 'downgradePlans','upgradePlans'));
}


public function showCustomerInvoices()
{
    // Get the logged-in customer's email from the session
    $customer = PartnerUser::where('email', Session::get('user_email'))->first();

    if (!$customer) { 
        return back()->withErrors('Customer not found.');
    }

    // Fetch invoices for the customer
    $invoices = Invoice::where('zoho_cust_id', $customer->zoho_cust_id)->get();

    // Fetch the customer's subscription if it exists
    $subscriptions = Subscription::where('zoho_cust_id', $customer->zoho_cust_id)->first();

    // Initialize $plans to null in case there is no subscription
    $plans = null;
    
    // Only fetch the plan if a subscription exists
    if ($subscriptions) {
        $plans = Plan::where('plan_id', $subscriptions->plan_id)->first();
    }

    return view('customerInvoices', compact('invoices', 'subscriptions', 'plans'));
}


public function addupdate(Request $request, $id)
    {
        // Find the customer by ID or fail if not found
        $customer = Partner::where('zohocust_id', $id)->firstOrFail();
    
        // Validate the request data
        $validatedData = $request->validate([
            'billing_street' => 'nullable|string',
            'billing_city' => 'nullable|string',
            'billing_state' => 'nullable|string',
            'billing_country' => 'nullable|string',
            'billing_zip' => 'nullable|string',
        ]);
    
        // Update the customer in the database
        $customer->update([
            'billing_street' => $validatedData['billing_street'] ?? $customer->billing_street,
            'billing_city' => $validatedData['billing_city'] ?? $customer->billing_city,
            'billing_state' => $validatedData['billing_state'] ?? $customer->billing_state,
            'billing_country' => $validatedData['billing_country'] ?? $customer->billing_country,
            'billing_zip' => $validatedData['billing_zip'] ?? $customer->billing_zip,
        ]);
    
        // Call Zoho API to update the customer data there
        try {
            $this->updateAddCustomerInZoho($customer); 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update customer in Zoho: ' . $e->getMessage());
        }

        // Save customer to the database
        $customer->save();

        // Redirect back with success message
        return redirect()->route('customer.details')->with('success', 'Customer updated successfully!');

    }

    // function profile(){
    //     return view('profile');
    // }

    private function updateAddCustomerInZoho($customer)
    {
        // Get Zoho access token
        $accessToken = $this->zohoService->getAccessToken();

        // Call Zoho API to update the customer
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken
        ])->put('https://www.zohoapis.com/billing/v1/customers/' . $customer->zohocust_id, [
            'organization_id' => config('services.zoho.organization_id'),
            'display_name' => $customer->customer_name,
            'billing_address' => [
                'street' => $customer->billing_street,
                'city' => $customer->billing_city,
                'state' => $customer->billing_state,
                'country' => $customer->billing_country,
                'zip' => $customer->billing_zip,
            ],
        ]);
   
        // Handle Zoho API response
        if (!$response->successful()) {
            throw new \Exception('Zoho API error: ' . $response->body());
        }

        return true;
    }
    public function editPayment()
    {
        $zoho_cust_id=Route::getCurrentRoute()->zoho_cust_id;
     
        // Zoho API credentials
        $accessToken = $this->zohoService->getAccessToken();

        $response = Http::withHeaders([ 'Authorization' => 'Zoho-oauthtoken ' .$accessToken,
            'Content-Type'  => 'application/json'
        ])->post(config('services.zoho.zoho_add_payment'), [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'customer_id' => $zoho_cust_id,
            'redirect_url' => route('zoho.callback')
            ]);

        if ($response->successful()) {
            $hostedPageData = $response->json();
          
            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
            return redirect()->to($hostedPageUrl);
        } else {
            return redirect()->back()->with('error', 'Failed to update payment details in Zoho.');
        }
    }

    
    
    public function handleZohoCallback(Request $request)
    {
        // Get the hostedpage_id from the callback query parameters
        $hostedPageId = $request->query('hostedpage_id');
        
        $accessToken = $this->zohoService->getAccessToken();
    
        // Fetch the hosted page details from Zoho
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->get('https://www.zohoapis.com/billing/v1/hostedpages/' . $hostedPageId);
    
        // Convert the response to JSON
        $hostedPageData = $response->json();
    
        // Check if the 'data' and 'payment_method' keys exist in the response
        if (!isset($hostedPageData['data']['payment_method'])) {
            return redirect()->route('profile')->with('error', 'Failed to retrieve payment method details from Zoho.');
        }
    
        // Extract the payment method details
        $paymentMethodData = $hostedPageData['data']['payment_method'];
    
        // Find the payment record by Zoho customer ID
        $payment = Payment::where('zoho_cust_id', $paymentMethodData['customer']['customer_id'])->first();
    
        if ($payment) {
            // Update the payment details in the local database
            $payment->payment_method_id = $paymentMethodData['payment_method_id'] ?? $payment->payment_method_id;
            $payment->expiry_year = $paymentMethodData['expiry_year'] ?? $payment->expiry_year;
            $payment->expiry_month = $paymentMethodData['expiry_month'] ?? $payment->expiry_month;
            $payment->last_four_digits = $paymentMethodData['last_four_digits'] ?? $payment->last_four_digits;
            
            // Save the updated payment details
            $payment->save();
    
            return redirect()->route('customer.details')->with('success', 'Payment details updated successfully!');
        } else {
            return redirect()->route('customer.details')->with('error', 'Failed to update payment details. Record not found.');
        }
    }
    
    public function upgrade(Request $request)
    {
        $validated = $request->validate([
            'zoho_cust_id' => 'required|string',
            'subscription_number' => 'required|string',
            'plan_name' => 'required|string',
            'amount' => 'required|numeric',
            'consent' => 'required|boolean',  
        ]);

    Term::create([
        'zoho_cust_id' => $validated['zoho_cust_id'],
        'zoho_cpid' => null,  // Set Zoho CPID as null for now
        'subscription_number' => $validated['subscription_number'],
        'ip_address' => $request->ip(),  // Get the IP address of the user
        'browser_agent' => $request->header('User-Agent'),  // Get the browser agent
        'consent' => $validated['consent'],  // Store the consent value
        'plan_name' => $validated['plan_name'],
        'amount' => $validated['amount'],
    ]);

        $accessToken = $this->zohoService->getAccessToken();
        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();
    
        $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
  
        if (!$subscription) {
            return back()->withErrors('Subscription not found.');
        }
    
        $planId = $request->input('plan_code');
    
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post(config('services.zoho.zoho_upgrade_subscription'), [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'subscription_id' => $subscription->subscription_id,
            'plan' => [
                'plan_code' => $planId
            ],
            'redirect_url' => url('thanks')
        ]);
        if ($response->successful()) {
            $hostedPageData = $response->json();
            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
            $hostedPageId = $hostedPageData['hostedpage']['hostedpage_id'];
   
            // Store the hostedpage_id in the session for retrieval
            Session::put('hostedpage_id', $hostedPageId);
    
            return redirect()->to($hostedPageUrl);
        } else {
            return redirect()->back()->withErrors('Failed to upgrade subscription: ' . $response->body());
        }
    }
    
    public function callback()
    {
        $accessToken = $this->zohoService->getAccessToken();
        $hostedPageId = Session::get('hostedpage_id');
    // dd($hostedPageId)
        if (!$hostedPageId) {
            return back()->withErrors('Hosted page ID is missing.');
        }
    
        // Retrieve the hosted page details from Zoho
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->get("https://www.zohoapis.com/billing/v1/hostedpages/{$hostedPageId}");
    
        if ($response->successful()) {
            $hostedPageData = $response->json();
           
            // Store the subscription, invoice, and payment details
            $this->upgradeSubscriptionData($hostedPageData);
            //return view('customerSubscriptions');
    
          
        } else {
            return back()->withErrors('Error retrieving hosted page data: ' . $response->body());
        }
    }


    public function upgradeSubscriptionData($data)
    {
        $subscriptionData = $data['data']['subscription'];
        $invoiceData = $data['data']['invoice'];
        $paymentMethodData = $invoiceData['payments'] ?? [];
        $cardData = $subscriptionData['card'];
        $paymentMethodId = $cardData['card_id'] ?? ($paymentMethodData['payment_method_id'] ?? null);
    
        // Fetch the existing subscription by Zoho subscription ID
        $existingSubscription = Subscription::where('subscription_id', $subscriptionData['subscription_id'])->first();
        
      

        if (!$existingSubscription) {
            $subscription = Subscription::create([
           'subscription_id' => $subscriptionData['subscription_id'],
           'subscription_number' => $subscriptionData['subscription_number'],
           'plan_id' => $subscriptionData['plan']['plan_id'],
           'invoice_id' => $invoiceData['invoice_id'],
           'payment_method_id' => $paymentMethodId,
           'next_billing_at' => $subscriptionData['next_billing_at'],
           'start_date' => $subscriptionData['start_date'],
           'zoho_cust_id' => $subscriptionData['customer_id'],
           'status' => $subscriptionData['status'], 
           'addon' => 0,
       ]);
   } else {
       $existingSubscription->update([
           'subscription_number' => $subscriptionData['subscription_number'],
           'plan_id' => $subscriptionData['plan']['plan_id'],
           'invoice_id' => $invoiceData['invoice_id'],
           'payment_method_id' => $paymentMethodId,
           'next_billing_at' => $subscriptionData['next_billing_at'],
           'start_date' => $subscriptionData['start_date'],
           'zoho_cust_id' => $subscriptionData['customer_id'],
           'status' => $subscriptionData['status'], 
           'addon' => 0,
       ]);
   }
// Check if an invoice record with the same invoice_id already exists
$existingInvoice = Invoice::where('invoice_id', $invoiceData['invoice_id'])->first();
$invoiceItems = json_encode($invoiceData['invoice_items'] ?? []);
$paymentItems = json_encode($invoiceData['payments'] ?? []);
if (!$existingInvoice) {
    // Create a new invoice record if it doesn't already exist
    Invoice::create([
        'invoice_id' => $invoiceData['invoice_id'],
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'], // Link to subscription
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'payment_details' => $paymentItems,
        'status' => $invoiceData['status'],
    ]);
} else {
    // Optionally, update the existing record if needed
    $existingInvoice->updateOrInsert([
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'],
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method_id' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'status' => $invoiceData['status'],
    ]);
}

  // Step 3: Store the credits from the invoice into the 'creditnotes' table
  $credits = $invoiceData['credits'] ?? [];
  if (!empty($credits)) {
      foreach ($credits as $credit) {
          CreditNote::updateOrCreate(
              ['creditnote_id' => $credit['creditnote_id']],
              [
                  'creditnote_number' => $credit['creditnotes_number'] ?? null,
                  'credited_date' => $credit['credited_date'] ?? null,
                  'invoice_number' => $credit['invoice_id'] ?? null,
                  'zoho_cust_id' => $subscriptionData['customer_id'],
                  'status' => 'credited', // Assuming this is a credited status
                  'credited_amount' => $credit['credited_amount'],
                  'balance' => 0, // Set the balance, or you can modify as per your requirements
              ]
          );
      }
  }
        // Step 4: Store payment method data in the 'payments' table (only if payment method exists)
        if (!empty($paymentMethodData) && isset($subscriptionData['card'])) {
            $cardData = $subscriptionData['card'];
            
            // Check if 'payments' exists in the 'invoice' section and is not empty
            $invoicePayments = $invoiceData['payments'] ?? [];
         
            if (!empty($invoicePayments)) {
                // Extract payment mode and amount from the first payment record (assuming single payment)
                $paymentDetails = $invoicePayments[0]; 
        
                // Check if a payment record with the same payment_method_id already exists
                $existingPayment = Payment::where('payment_method_id', $cardData['card_id'])->first();
        
                if (!$existingPayment) {
                    // Create a new payment record if it doesn't already exist
                    Payment::create([
                        'payment_method_id' => $cardData['card_id'] ?? null,// Use null if card_id not present
                        'type' => $cardData['card_type'] ?? null, // Correct field name
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  // Extract payment mode from payments array
                        'amount' => $paymentDetails['amount'] ?? null,              // Extract amount from payments array
                        'invoice_id' => $invoiceData['invoice_id'],  
                        'payment_id' =>$paymentDetails['payment_id'],
 
                    ]);
                } else {
                    // Optionally, update the existing record if needed
                    $existingPayment->updateOrInsert([
                        'payment_method_id' => $paymentDetails['payment_id'] ?? null,
                        'type' => $cardData['card_type'] ?? null,
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  // Extract payment mode from payments array
                        'amount' => $paymentDetails['amount'] ?? null,              // Extract amount from payments array
                        'invoice_id' => $invoiceData['invoice_id'],    
                         'payment_id' =>$paymentDetails['payment_id']
                    ]);
                }
            }
        }

        // Return success message or redirect as needed
        return redirect()->route('thanksup')->with('success', 'Subscription successfully completed!');
    }
    public function thanksup(){
        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();
    
       
        $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
        
        $plans = null;
        if ($subscriptions) {
            $plans = Plan::where('plan_id', $subscriptions->plan_id)->first();
        }

        $invoice = Invoice::where('zoho_cust_id', $customer->zohocust_id)
        ->where('subscription_id', $subscriptions->subscription_id) // Ensure there's a subscription_id column in your Invoice table
        ->first();

        return view('thanks', compact('subscriptions', 'plans','invoice'));
    }
    public function filterSubscriptions(Request $request)
    {
        
        $search = $request->input('search');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $perPage = $request->input('show', 10); 
    
     
        $query = DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
            ->join('partners', 'subscriptions.zoho_cust_id', '=', 'partners.zohocust_id')
            ->select(
                'subscriptions.subscription_id',
                'subscriptions.subscription_number',
                'partners.company_name',
                'plans.plan_name',
                'plans.plan_price',
                'subscriptions.start_date',
                'subscriptions.next_billing_at',
                'subscriptions.status'
            );
    
        
        if ($startDate) {
            $query->whereDate('subscriptions.start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('subscriptions.start_date', '<=', $endDate);
        }
    
       
        if ($search) {
            $query->where('plans.plan_name', 'LIKE', "%{$search}%");
        }
    
        // Paginate the results
        $subscriptions = $query->paginate($perPage);
    
        // Pass the data back to the view
        return view('subscription', compact('subscriptions', 'search', 'startDate', 'endDate'));
    }
    
    public function filterInvoices(Request $request)
{
   
    $search = $request->input('search');
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $perPage = $request->input('show', 10); 

    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 

    $query = DB::table('invoices')->where('zoho_cust_id', $customer->zohocust_id);


    if ($startDate) {
        $query->whereDate('invoice_date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('invoice_date', '<=', $endDate);
    }
    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->orWhere('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) LIKE ?", ["%{$search}%"]);
        });
    }

   
    $invoices = $query->paginate($perPage);

    return view('customerinvoices', compact('invoices', 'search', 'startDate', 'endDate'));
}


public function showCustomerCredits()
{
    // Get the logged-in customer's email from the session
    $partneruser= PartnerUser::where('email', Session::get('user_email'))->first();

    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }

    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();
    $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();
    
    // Check if there are any credit notes
    if ($creditnotes->isEmpty()) {
        // No credit notes found
        $customers = null;
    } else {
        // Fetch customer details based on the first credit note
        $customers = Partner::where('zohocust_id', $creditnotes->first()->zoho_cust_id)->first();
    }

    return view('creditnotes', compact('creditnotes', 'customers'));
}

function pdfdownload($creditnote_id)
{
   
     $accessToken = $this->zohoService->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken
        ])->get("https://www.zohoapis.com/billing/v1/creditnotes/{$creditnote_id}?accept=pdf");
        
       
        if ($response->successful()) {
           
            $pdfPath = 'credit_note.pdf'; 
            Storage::disk('local')->put($pdfPath, $response->body());
        
            
            return response()->download(storage_path("app/{$pdfPath}"));
        } else {
          
            return response()->json(['error' => 'Unable to download PDF'], $response->status());
        }
}

public function filtercredits(Request $request)
{
    // Get the logged-in customer based on their email
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 

    $query = Creditnote::where('zoho_cust_id', $customer->zohocust_id);

    // Apply filters based on user input
    if ($request->has('startDate') && $request->startDate) {
        $query->where('credited_date', '>=', $request->startDate);
    }

    if ($request->has('endDate') && $request->endDate) {
        $query->where('credited_date', '<=', $request->endDate);
    }

    if ($request->has('search') && $request->search) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('creditnote_number', 'LIKE', '%' . $searchTerm . '%');
            //   ->orWhere('company_name', 'LIKE', '%' . $searchTerm . '%');
            //   ->orWhere('invoice_number', 'LIKE', '%' . $searchTerm . '%');
        });
    }

    // Apply limit for number of results (show entries)
    $showEntries = $request->input('show', 10); // Default to 10
    $creditnotes = $query->paginate($showEntries);
    $customers = Partner::where('zohocust_id', $customer->zohocust_id)->first();
    // Pass filtered credit notes and customer details to the view
    return view('creditnotes', compact('creditnotes', 'customers'));
}
public function showCustomerSupport(Request $request)
{
   
    $partneruser= PartnerUser::where('email', Session::get('user_email'))->first();

    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }

    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    $supportsQuery = Support::where('zoho_cust_id', $customer->zohocust_id);

    if ($request->filled('startDate')) {
        $supportsQuery->whereDate('date', '>=', $request->startDate);
    }

    if ($request->filled('endDate')) {
        $supportsQuery->whereDate('date', '<=', $request->endDate);
    }

    if ($request->filled('search')) {
        $supportsQuery->where('request_type', 'like', '%' . $request->search . '%');
    }

    $supports = $supportsQuery->paginate($request->input('show', 10));

    return view('support', compact('supports', 'customer'));
}



public function ticketstore(Request $request)
{

    $request->validate([
        'message' => 'required|string|max:1000',
    ]);
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    $zohocpid = $partneruser->zoho_cpid;
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();  

    $zohoCustId = $customer->zohocust_id;

    $existingTicket = Support::where('zoho_cust_id', $zohoCustId)
                             ->where('request_type', 'Custom Support')
                             ->where('status', 'open')
                             ->first();

    if ($existingTicket) {
        return back()->withErrors('You already raised a support ticket');
    }

    Support::create([
        'date' => now(),
        'request_type' => 'Custom Support', 
        'message' => $request->input('message'),
        'status' => 'open',
        'zoho_cust_id' => $zohoCustId,
        'zoho_cpid' =>  $zohocpid ,
    ]);

    return redirect()->route('show.support')->with('success', 'Support ticket created successfully.');
}
public function downgrade(Request $request)
{
    $request->validate([
        'plan_id' => 'required|exists:plans,plan_id',
    ]);
   
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 

    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
  
    if (!$subscription) {
        return back()->withErrors('Subscription not found.');
    }

    $selectedPlan = Plan::where('plan_id', $request->plan_id)->first();
  
    if (!$selectedPlan) {
        return back()->withErrors('Plan not found.');
    }

    $openTicket = Support::where('zoho_cust_id', $customer->zohocust_id)
        ->where('request_type', 'Downgrade')
        ->where('status', 'open')
        ->first();

    if ($openTicket) {
        // Return with an error message if an open ticket exists
        return back()->withErrors('An open downgrade request already exists');
    }

    // Create a new support ticket for downgrade
    Support::create([
        'date' => now(),
        'request_type' => 'Downgrade', 
        'subscription_number' => $subscription->subscription_number,
        'message' => 'I would like to downgrade my subscription to the ' . $selectedPlan->plan_name . '. Please contact me with steps to downgrade.',
        'status' => 'open',
        'zoho_cust_id' => $customer->zohocust_id,
        'zoho_cpid' =>  $partneruser->zoho_cpid, 
    ]);

    // Redirect with a success message
    return redirect()->route('show.support')->with('success', 'Downgrade request submitted successfully.');
}
public function supportticket()
{
    $supports = DB::table('supports')
    ->join('partners', 'supports.zoho_cust_id', '=', 'partners.zohocust_id')
    ->join('partner_users', 'partners.zohocust_id', '=', 'partner_users.zoho_cust_id') 
    ->leftJoin('subscriptions', 'supports.subscription_number', '=', 'subscriptions.subscription_number') 
    ->select(
        'supports.*',
        'partners.company_name',
        'partners.customer_name',
        'partner_users.email as customer_email', 
        'subscriptions.plan_id',
        'subscriptions.subscription_id',
        DB::raw('CASE WHEN subscriptions.subscription_number IS NULL THEN "No Subscription" ELSE subscriptions.subscription_number END as subscription_status') // Handle empty subscription_number
    )
    ->where(function ($query) {
        $query->whereNull('partner_users.zoho_cpid') // No zoho_cpid in partner_users
            ->orWhere('supports.zoho_cpid', '=', 'partner_users.zoho_cpid'); // zoho_cpid matches
    })
    ->get();

    $planCodes = DB::table('plans')->pluck('plan_code', 'plan_name')->toArray(); 
  
    foreach ($supports as $support) {
        $message = $support->message;

        $start = strpos($message, 'the') + strlen('the');
        $end = strpos($message, '.', $start);

        if ($start !== false && $end !== false) {
            $planName = trim(substr($message, $start, $end - $start));

           
            $planCode = $planCodes[$planName] ?? null; 

            $support->plan_code = $planCode;
        } else {
            $support->plan_code = null; 
        }

        if (empty($support->subscription_status) || $support->subscription_status === "No Subscription") {
            $support->plan_code = "No Plan"; 
        }
    }

    return view('supportticket', compact('supports'));
}

public function supportticketfilter(Request $request)
{
     
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $search = $request->input('search');
    $show = $request->input('show', 10); 

  
    $query = DB::table('supports')
        ->join('partners', 'supports.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'supports.*', 
            'partners.company_name' 
        );

   
    if ($startDate) {
        $query->whereDate('supports.date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('supports.date', '<=', $endDate);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('supports.subscription_number', 'LIKE', "%{$search}%")
              ->orWhere('partners.company_name', 'LIKE', "%{$search}%");
             
        });
    }
  
    $supports = $query->paginate($show);

    return view('supportticket', compact('supports'));
}
public function downgradesub(Request $request)
{
    $accessToken = $this->zohoService->getAccessToken();
    
    $subscription_id = $request->input('subscription_id');
    $subscription_number=$request->input('subscription_number');
    $planId = $request->input('plan_code');
    $customerName = $request->input('customer_name'); 
    $customerEmail = $request->input('customer_email'); 

    $response = Http::withHeaders([
        'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        'Content-Type' => 'application/json'
    ])->post(config('services.zoho.zoho_upgrade_subscription'), [
        'organization_id' => config('services.zoho.zoho_org_id'),
        'subscription_id' => $subscription_id,
        'plan' => [
            'plan_code' => $planId
        ],
        'redirect_url' => url('subdown')
    ]);

    if ($response->successful()) {
        $hostedPageData = $response->json();
        $hostedPageUrl = $hostedPageData['hostedpage']['url'];
        $hostedPageId = $hostedPageData['hostedpage']['hostedpage_id'];

        // Store the hostedpage_id in the session for retrieval
        Session::put('hostedpage_id', $hostedPageId);

        return redirect()->to($hostedPageUrl);
    } else {
        return redirect()->back()->withErrors('Failed to downgrade subscription: ' . $response->body());
    }
}
public function retrievedowntHostedPage()
    {
        $accessToken = $this->zohoService->getAccessToken();
        $hostedPageId = Session::get('hostedpage_id'); // Retrieve the hostedpage_id from the session

        if (!$hostedPageId) {
            return back()->withErrors('Hosted page ID is missing.');
        }

       
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json'
        ])->get("https://www.zohoapis.com/billing/v1/hostedpages/{$hostedPageId}");
 
        if ($response->successful()) {
            $hostedPageData = $response->json();
         
                $this->downgradeSubscriptionData($hostedPageData); // Pass the JSON data to store it
                return redirect()->route('Support.Ticket');
        } else {
            return back()->withErrors('Error retrieving hosted page data: ' . $response->body());
        }
    }
    public function downgradeSubscriptionData($data)
    {
        $subscriptionData = $data['data']['subscription'];
        $invoiceData = $data['data']['invoice'];
        $paymentMethodData = $invoiceData['payments'] ?? [];
        $cardData = $subscriptionData['card'];
        $paymentMethodId = $cardData['card_id'] ?? ($paymentMethodData['payment_method_id'] ?? null);
    
        // Fetch the existing subscription by Zoho subscription ID
        $existingSubscription = Subscription::where('subscription_id', $subscriptionData['subscription_id'])->first();

        if (!$existingSubscription) {
            $subscription = Subscription::create([
           'subscription_id' => $subscriptionData['subscription_id'],
           'subscription_number' => $subscriptionData['subscription_number'],
           'plan_id' => $subscriptionData['plan']['plan_id'],
           'invoice_id' => $invoiceData['invoice_id'],
           'payment_method_id' => $paymentMethodId,
           'next_billing_at' => $subscriptionData['next_billing_at'],
           'start_date' => $subscriptionData['start_date'],
           'zoho_cust_id' => $subscriptionData['customer_id'],
          'status' => $subscriptionData['status'], 
       ]);
   } else {
       $existingSubscription->update([
           'subscription_number' => $subscriptionData['subscription_number'],
           'plan_id' => $subscriptionData['plan']['plan_id'],
           'invoice_id' => $invoiceData['invoice_id'],
           'payment_method_id' => $paymentMethodId,
           'next_billing_at' => $subscriptionData['next_billing_at'],
           'start_date' => $subscriptionData['start_date'],
           'zoho_cust_id' => $subscriptionData['customer_id'],
           'status' => $subscriptionData['status'], 
       ]);
   }
// Check if an invoice record with the same invoice_id already exists
$existingInvoice = Invoice::where('invoice_id', $invoiceData['invoice_id'])->first();
$invoiceItems = json_encode($invoiceData['invoice_items'] ?? []);
$paymentItems = json_encode($invoiceData['payments'] ?? []);
if (!$existingInvoice) {
    // Create a new invoice record if it doesn't already exist
    Invoice::create([
        'invoice_id' => $invoiceData['invoice_id'],
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'], // Link to subscription
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'payment_details' => $paymentItems,
        'status' => $invoiceData['status'],
    ]);
} else {
    // Optionally, update the existing record if needed
    $existingInvoice->updateOrInsert([
        'invoice_date' => $invoiceData['date'],
        'invoice_number' => $invoiceData['invoice_number'],
        'subscription_id' => $subscriptionData['subscription_id'],
        'credits_applied' => $invoiceData['credits_applied'],
        'discount' => $subscription->discount ?? null,
        'payment_made' => $invoiceData['payment_made'],
        'payment_method_id' => $paymentMethodId,
        'invoice_link' => $invoiceData['invoice_url'],
        'zoho_cust_id' => $subscriptionData['customer_id'],
        'invoice_items' => $invoiceItems,
        'status' => $invoiceData['status'],
    ]);
}

  // Step 3: Store the credits from the invoice into the 'creditnotes' table
  $credits = $invoiceData['credits'] ?? [];
  if (!empty($credits)) {
      foreach ($credits as $credit) {
          CreditNote::updateOrCreate(
              ['creditnote_id' => $credit['creditnote_id']],
              [
                  'creditnote_number' => $credit['creditnotes_number'] ?? null,
                  'credited_date' => $credit['credited_date'] ?? null,
                  'invoice_number' => $credit['invoice_id'] ?? null,
                  'zoho_cust_id' => $subscriptionData['customer_id'],
                  'status' => 'credited', // Assuming this is a credited status
                  'credited_amount' => $credit['credited_amount'],
                  'balance' => 0, // Set the balance, or you can modify as per your requirements
              ]
          );
      }
  }
        // Step 4: Store payment method data in the 'payments' table (only if payment method exists)
        if (!empty($paymentMethodData) && isset($subscriptionData['card'])) {
            $cardData = $subscriptionData['card'];
            
            // Check if 'payments' exists in the 'invoice' section and is not empty
            $invoicePayments = $invoiceData['payments'] ?? [];
         
            if (!empty($invoicePayments)) {
                // Extract payment mode and amount from the first payment record (assuming single payment)
                $paymentDetails = $invoicePayments[0]; 
        
                // Check if a payment record with the same payment_method_id already exists
                $existingPayment = Payment::where('payment_method_id', $cardData['card_id'])->first();
        
                if (!$existingPayment) {
                    // Create a new payment record if it doesn't already exist
                    Payment::create([
                        'payment_method_id' => $cardData['card_id'] ?? null,// Use null if card_id not present
                        'type' => $cardData['card_type'] ?? null, // Correct field name
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  // Extract payment mode from payments array
                        'amount' => $paymentDetails['amount'] ?? null,              // Extract amount from payments array
                        'invoice_id' => $invoiceData['invoice_id'],  
                        'payment_id' =>$paymentDetails['payment_id'],
 
                    ]);
                } else {
                    // Optionally, update the existing record if needed
                    $existingPayment->updateOrInsert([
                        'payment_method_id' => $paymentDetails['payment_id'] ?? null,
                        'type' => $cardData['card_type'] ?? null,
                        'zoho_cust_id' => $subscriptionData['customer_id'],
                        'last_four_digits' => $cardData['last_four_digits'] ?? null,
                        'expiry_year' => $cardData['expiry_year'] ?? null,
                        'expiry_month' => $cardData['expiry_month'] ?? null,
                        'payment_gateway' => $cardData['payment_gateway'] ?? null,
                        'status' => $invoiceData['status'],
                        'payment_mode' => $paymentDetails['payment_mode'] ?? null,  
                        'amount' => $paymentDetails['amount'] ?? null, 
                        'invoice_id' => $invoiceData['invoice_id'],    
                         'payment_id' =>$paymentDetails['payment_id']
                    ]);
                }
            }
        }
        DB::table('supports')
        ->where('subscription_number', $subscriptionData['subscription_number'])
        ->where('request_type', 'Downgrade') 
        ->update(['status' => 'Completed']);

        $customerName = $subscriptionData['customer']['display_name'] ?? 'Customer'; 
        $customerEmail = $subscriptionData['customer']['email'] ?? null;  
        $planId = $subscriptionData['plan']['plan_id'];

        if ($customerEmail) {
            Mail::to($customerEmail)->send(new SubscriptionDowngrade($customerName, $planId));
        }

        return redirect()->route('Support.Ticket')->with('success', 'Subscription successfully completed!');
    }

    public function addons(Request $request)
    {
        $validated = $request->validate([
            'zoho_cust_id' => 'required|string',
            'subscription_number' => 'required|string',
            'plan_name' => 'required|string',
            'amount' => 'required|numeric',
            'consent' => 'required|boolean',  
        ]);

    Term::create([
        'zoho_cust_id' => $validated['zoho_cust_id'],
        'zoho_cpid' => null,  
        'subscription_number' => $validated['subscription_number'],
        'ip_address' => $request->ip(), 
        'browser_agent' => $request->header('User-Agent'), 
        'consent' => $validated['consent'], 
        'plan_name' => $validated['plan_name'],
        'amount' => $validated['amount'],
    ]);

        $planId = $request->input('plan_id'); 
       
        $accessToken = $this->zohoService->getAccessToken();

        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

        $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();

        if (!$subscriptions) {
            return back()->withErrors('Subscription not found.');
        }
        $plans = Plan::where('plan_id', $planId)->first();

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json',
            'organization_id' => config('services.zoho.zoho_org_id')
        ])->post(config('services.zoho.zoho_addon'), [
          
            'subscription_id' => $subscriptions->subscription_id,
            'addons' => [
                [
                    'addon_code' => $plans->addon_code,
                    'quantity'=> 1,
                ]
            ],
            'redirect_url' => url('addonthanks')
        ]);
    
        if ($response->successful()) {
            $hostedPageData = $response->json();

            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
            
            $hostedPageId = $hostedPageData['hostedpage']['hostedpage_id'];

            Session::put('hostedpage_id', $hostedPageId); 
            return redirect()->to($hostedPageUrl);

        } else {
            return back()->withErrors('Error creating subscription: ' . $response->body());
        }
    }

    public function retrieveaddonHostedPage()
    {
        
        $accessToken = $this->zohoService->getAccessToken();
        $hostedPageId = Session::get('hostedpage_id'); 

        if (!$hostedPageId) {
            return back()->withErrors('Hosted page ID is missing.');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json'
        ])->get("https://www.zohoapis.com/billing/v1/hostedpages/{$hostedPageId}");

        if ($response->successful()) {
            $hostedPageData = $response->json();
                $this->addonSubscriptionData($hostedPageData); 
                return redirect()->route('customer.subscriptions');
        } else {
            return back()->withErrors('Error retrieving hosted page data: ' . $response->body());
        }
    }

    public function addonSubscriptionData($data)
    {
        $subscriptionData = $data['data']['subscription'];
        $invoiceData = $data['data']['invoice'];
        $paymentMethodData = $invoiceData['payments'] ?? [];
        $cardData = $subscriptionData['card'];
        $paymentMethodId = $cardData['card_id'] ?? ($paymentMethodData['payment_method_id'] ?? null);
        
        
        $existingSubscription = Subscription::where('subscription_id', $subscriptionData['subscription_id'])->first();
    
        if (!$existingSubscription) {
            $subscription = Subscription::create([
                'subscription_id' => $subscriptionData['subscription_id'],
                'subscription_number' => $subscriptionData['subscription_number'],
                'plan_id' => $subscriptionData['plan']['plan_id'],
                'invoice_id' => $invoiceData['invoice_id'],
                'payment_method_id' => $paymentMethodId,
                'next_billing_at' => $subscriptionData['next_billing_at'],
                'start_date' => $subscriptionData['start_date'],
                'zoho_cust_id' => $subscriptionData['customer_id'],
                'status' => $subscriptionData['status'], 
                'addon' => 1, 
            ]);
        } else {
            $existingSubscription->update([
                'subscription_number' => $subscriptionData['subscription_number'],
                'plan_id' => $subscriptionData['plan']['plan_id'],
                'invoice_id' => $invoiceData['invoice_id'],
                'payment_method_id' => $paymentMethodId,
                'next_billing_at' => $subscriptionData['next_billing_at'],
                'start_date' => $subscriptionData['start_date'],
                'zoho_cust_id' => $subscriptionData['customer_id'],
                'status' => $subscriptionData['status'], 
                'addon' => 1, 
            ]);
        }
    
    
        return redirect()->route('customer.subscriptions')->with('success', 'Subscription successfully completed!');
    }

    public function updatePasswordinprofile(Request $request)
{
   
    $request->validate([
        'email' => 'required|email',
        'current_password' => 'required',
        'new_password' => 'required|confirmed|min:6',
    ]);
   
    $partneruser = PartnerUser::where('email', $request->email)->first();

    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    if (! $partneruser || !Hash::check($request->current_password,  $partneruser->password)) {
        return redirect()->back()->withErrors(['current_password' => 'Invalid current password or email.']);
    }

   $partneruser->password = Hash::make($request->new_password);
   

    if ($partneruser->save()) {
        return redirect()->route('customer.details')->with('success', 'Your password has been successfully updated.');
    }

    return redirect()->route('customer.details')->withErrors(['error' => 'Failed to update the password.']);
}


public function showUpgradePreview(Request $request)
{
    $planCode = $request->input('plan_code');
  
    $newPlan = Plan::where('plan_code', $planCode)->first();
   
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
    $plan=Plan::where ('plan_id',$subscription->plan_id)->first();
    return view('upgrade-preview', compact('subscription', 'newPlan','plan'));
}
public function showsubscribePreview(Request $request)
{
    $planCode = Session::get('plan_code');
    $email = Session::get('email');

    $planCode = $request->input('plan_code');
 
    $newPlan = Plan::where('plan_code', $planCode)->first();
  
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }

        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    return view('subscribe-preview', compact('newPlan','customer','partneruser'));
}
public function processUpgrade(Request $request)
{
    $planCode = $request->input('plan_code');

    return redirect()->route('subscription.details')->with('success', 'Your subscription has been upgraded successfully.');
}

public function showAddonPreview(Request $request)
{
    $planCode = $request->input('plan_code');

    $newPlan = Plan::where('plan_code', $planCode)->first();
  
    if (!$newPlan) {
        return back()->withErrors('Plan not found.');
    }

    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
    $plan = Plan::where('plan_id', $subscription->plan_id)->first();

    return view('addon-preview', compact('subscription', 'newPlan', 'plan'));
}

public function companyinfo()
{
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 
    
    $company = CompanyInfo::where('zoho_cust_id', $partneruser->zoho_cust_id)->firstOrNew(['zoho_cust_id' => $partneruser->zoho_cust_id]);

    return view('companyinfo', compact('company', 'customer'));
}
public function storeTerms(Request $request)
{
   
    $validated = $request->validate([
        'zoho_cust_id' => 'required|string',
        'subscription_number' => 'required|string',
        'plan_name' => 'required|string',
        'amount' => 'required|numeric',
        'consent' => 'required|boolean',  
    ]);
    return redirect(route('addon'))->with('success', 'Your agreement has been recorded successfully.');
}
public function updatecompanyinfo(Request $request)
{

    $request->validate([
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'company_name' => 'required|string|max:255',
        'tune_link' => 'nullable|url',
        'landing_page_url' => 'required|url',
        'landing_page_url_spanish' => 'nullable|url',
    ]);

    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();   

    $data = [
        'company_name' => $request->company_name,
        'landing_page_uri' => $request->landing_page_url,
        'landing_page_url_spanish' => $request->landing_page_url_spanish,
        'tune_link' => $request->tune_link,
        'uploaded_by' => Session::get('user_email'),
        'zoho_cust_id' => $customer->zohocust_id,
    ];

    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('logos', 'public');
        $data['logo_image'] = $logoPath;
    }

    $companyInfo = CompanyInfo::updateOrCreate(
        ['zoho_cust_id' => $customer->zohocust_id], 
        $data
    );
  
    if (ProviderData::where('zoho_cust_id', $customer->zohocust_id)->exists()) {
        $customer->first_login = false;
        $customer->save();
    }
    
    return redirect()->back()->with('success', 'Company information updated successfully.');
}

public function ProviderData()
{
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();     
    $providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->get(); 
    $totalCount = ProviderData::where('uploaded_by', $customer->customer_name)->count();  

   return view('provider',compact('providerData', 'customer','totalCount'));
}

public function uploadCsv(Request $request)
{

    try {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if (!$request->hasFile('csv_file')) {
            return response()->json(['error' => 'No file received.'], 400);
        }

        $file = $request->file('csv_file');
        $fileName = $file->getClientOriginalName(); 
        $fileSize = $file->getSize();
        $filePath = $file->storeAs('uploads/csv_files', $fileName, 'public');

        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

        $lineCount = 0;
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            // Skip the header row
            $headerSkipped = false;
            while (($data = fgetcsv($handle)) !== false) {
                if (!$headerSkipped) {
                    $headerSkipped = true; 
                    continue;
                }
   
                if (array_filter($data)) {
                    $lineCount++;
                }
            }
            fclose($handle);
        }
    
        $providerData = new ProviderData();
        $providerData->file_name = $fileName;
        $providerData->file_size =  $fileSize;
        $providerData->url = $filePath;
        $providerData->zoho_cust_id = $customer->zohocust_id;
        $providerData->uploaded_by = $customer->customer_name;
        $providerData->zip_count = $lineCount;
        $providerData->save();

        
        if (CompanyInfo::where('zoho_cust_id', $customer->zohocust_id)->exists()) {
            $customer->first_login = false;
            $customer->save();
        }
        $updatedData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->get();

        Log::info('Updated Data:', $updatedData->toArray());

        $totalCount = ProviderData::where('uploaded_by', $customer->customer_name)->count();

        return response()->json([
            'success' => 'File uploaded successfully!',
            'providerData' => $updatedData,
            'totalCount' => $totalCount,
        ]);
    } catch (\Exception $e) {
        Log::error('CSV Upload Error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'An unexpected error occurred.'], 500);
    }
}

public function ProviderDatafilter(Request $request)
{

     $query = ProviderData::query();

     if ($request->has('search') && $request->search != '') {
         $query->where('file_name', 'like', '%' . $request->search . '%');
     }

     if ($request->has('start_date') && $request->start_date != '') {
         $query->whereDate('created_at', '>=', $request->start_date);
     }
 
     if ($request->has('end_date') && $request->end_date != '') {
         $query->whereDate('created_at', '<=', $request->end_date);
     }

     $perPage = $request->get('per_page', 10);

     $providerData = $query->paginate($perPage);

      $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();  
     $totalCount = ProviderData::where('uploaded_by', $customer->customer_name)->count();  
     $providerData->appends([
         'search' => $request->get('search'),
         'start_date' => $request->get('start_date'),
         'end_date' => $request->get('end_date'),
         'per_page' => $request->get('per_page')
     ]);

     return view('provider', compact('providerData','totalCount'));
}

public function show($zohocust_id, Request $request)
{
    $customer = Partner::where('zohocust_id', $zohocust_id)->firstOrFail();

    $partnerUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
        ->whereNotNull('zoho_cpid') // Ensure zoho_cpid is not null
        ->get();

        $companyInfo = DB::table('company_info')
        ->where('zoho_cust_id', $customer->zohocust_id)
        ->first();

    //$providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->first();
    $providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->paginate(10);

    $normalUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
        ->whereNull('zoho_cpid') // Ensure zoho_cpid is null
        ->first();
    $partnerUsers = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)->get();
  
        
    if ($normalUser) {
        $customer->email = $normalUser->email;

    }
    
    $selectedSection = $request->query('section', 'overview');

    // Fetch the current subscription and its associated plan
    $subscriptions = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->join('partners', 'subscriptions.zoho_cust_id', '=', 'partners.zohocust_id')
        ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
        ->select(
            'subscriptions.subscription_id',
            'subscriptions.subscription_number',
            'partners.company_name',
            'plans.plan_name',
            'plans.plan_price',
            'subscriptions.start_date',
            'subscriptions.next_billing_at',
            'subscriptions.status',
            'partners.zohocust_id',
        )
        ->get(); 

    $currentSubscription = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
        ->select('plans.plan_price', 'plans.plan_id')
        ->first();
    
    // Fetch plans with a higher price than the current subscribed plan
    $upgradePlans = $currentSubscription 
        ? Plan::where('plan_price', '>', $currentSubscription->plan_price)->get() 
        : [];
   
    $invoices = Invoice::where('zoho_cust_id', $customer->zohocust_id)->get();
    $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();
    $affiliates = DB::table('partner_affiliates')
        ->join('affiliates', 'partner_affiliates.affiliate_id', '=', 'affiliates.id')  
        ->where('partner_affiliates.partner_id', $customer->zohocust_id)  
        ->select(
            'affiliates.isp_affiliate_id',
            'affiliates.domain_name'
        )
        ->get();

    $plans = DB::table('plans')->select('plan_code', 'plan_name', 'plan_price')->get();

    return view('customer-show', compact(
        'customer',
        'subscriptions',
        'invoices',
        'creditnotes',
        'affiliates',
        'selectedSection',
        'partnerUser',
        'plans',
         'upgradePlans',
         'partnerUsers',
         'companyInfo',
         'providerData'

    ));
}


public function customfilter(Request $request)
{
    // Start building the query for customers
    $customerQuery = Partner::query();

    // Apply the zohocust_id filter if provided
    if ($request->filled('zohocust_id')) {
        $customerQuery->where('zohocust_id', $request->zohocust_id);
    }

    // Apply date filters if provided
    if ($request->filled('start_date')) {
        $customerQuery->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $customerQuery->whereDate('created_at', '<=', $request->end_date);
    }

    // Apply search filter for company name if provided
    if ($request->filled('search')) {
        $customerQuery->where('company_name', 'like', '%' . $request->search . '%');
    }

    // Paginate results based on user input, default is 10
    $customers = $customerQuery->paginate($request->input('rows_to_show', 10));

    // Pass the customer data to the view
    return view('cust', compact('customers'));
}

public function termslog()
{
    $terms = DB::table('terms')
        ->join('partners', 'terms.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'terms.*',
            'partners.company_name',
            'partners.customer_name'
        )
        ->get();

    return view('terms-log', compact('terms'));
}
public function filterTermsLog(Request $request)
{
 
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $search = $request->input('search');
    $showEntries = $request->input('show', 10); 

    $query = DB::table('terms')
        ->join('partners', 'terms.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'terms.*',
            'partners.company_name',
            'partners.customer_name'
        );

    if ($startDate) {
        $query->whereDate('terms.created_at', '>=', $startDate);
    }

    if ($endDate) {
        $query->whereDate('terms.created_at', '<=', $endDate);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('terms.subscription_number', 'like', '%' . $search . '%')
              ->orWhere('partners.company_name', 'like', '%' . $search . '%')
              ->orWhere('partners.customer_name', 'like', '%' . $search . '%')
              ->orWhere('terms.plan_name', 'like', '%' . $search . '%');
        });
    }

    $terms = $query->paginate($showEntries);

    return view('terms-log', compact('terms'));
}


public function filterSubscriptionsnav(Request $request)
{
    $zohocust_id = $request->input('zohocust_id');
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $perPage = $request->input('rows_to_show', 10); 

 
    $query = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->join('partners', 'subscriptions.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'subscriptions.subscription_id',
            'subscriptions.subscription_number',
            'partners.company_name',
            'plans.plan_name',
            'plans.plan_price',
            'subscriptions.start_date',
            'subscriptions.next_billing_at',
            'subscriptions.status'
        );


    
    if ($startDate) {
        $query->whereDate('subscriptions.start_date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('subscriptions.start_date', '<=', $endDate);
    }

   
    if ($search) {
        $query->where('plans.plan_name', 'LIKE', "%{$search}%");
    }

    $subscriptions = $query->paginate($perPage);

    $customer = Partner::where('zohocust_id', $zohocust_id)->first();

    $partnerUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
    ->whereNotNull('zoho_cpid') 
    ->get();

    $invoices = Invoice::where('zoho_cust_id', $customer->zohocust_id)->get();

    $currentSubscription = DB::table('subscriptions')
    ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
    ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
    ->select('plans.plan_price', 'plans.plan_id')
    ->first();

// Fetch plans with a higher price than the current subscribed plan
$upgradePlans = $currentSubscription 
    ? Plan::where('plan_price', '>', $currentSubscription->plan_price)->get() 
    : [];


    $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();

    $affiliates = DB::table('partner_affiliates')
    ->join('affiliates', 'partner_affiliates.affiliate_id', '=', 'affiliates.id')  
    ->where('partner_affiliates.partner_id', $customer->zohocust_id)  
    ->select(
        'affiliates.isp_affiliate_id',
        'affiliates.domain_name'
    )
    ->get();
    $selectedSection = 'subscriptions'; 
    $plans = DB::table('plans')->select('plan_code', 'plan_name', 'plan_price')->get();
    $partnerUsers = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)->get();
    $companyInfo = DB::table('company_info')
    ->where('zoho_cust_id', $customer->zohocust_id)
    ->first();

    $providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->first();

 
    return view('customer-show', compact('customer','partnerUsers', 
    'subscriptions','selectedSection', 'affiliates', 'search', 'startDate',
     'endDate', 'invoices','creditnotes', 'partnerUser','plans','upgradePlans' ,'companyInfo',
     'providerData'));
}   

    
public function filterInvoicesnav(Request $request)
{
    // Get the search term and dates
    $zohocust_id = $request->input('zohocust_id');
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $perPage = $request->input('rows_to_show', 10); 
    
    $query = DB::table('invoices')
    ->join('partners', 'invoices.zoho_cust_id', '=', 'partners.zohocust_id')
    ->select(
        'invoices.*',
        'partners.company_name',
        DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) AS plan_name"), // Extracting plan name from JSON
        DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].price')) AS plan_price"),
        DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_details, '$[0].payment_mode')) AS payment_mode")
    );


    // Apply the date filters if they exist
    if ($startDate) {
        $query->whereDate('invoice_date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('invoice_date', '<=', $endDate);
    }

    // Apply the search filter on the plan name within the JSON field 'invoice_items'
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('invoices.invoice_number', 'LIKE', "%{$search}%")
              ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) LIKE ?", ["%{$search}%"]); // Searching plan_name from JSON
        });
    }

    // Paginate the results
    $invoices = $query->paginate($perPage);

    $customer = Partner::where('zohocust_id', $zohocust_id)->first();
    $partnerUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
    ->whereNotNull('zoho_cpid') // Ensure zoho_cpid is not null
    ->get();

    $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->get();
        $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();
    $selectedSection = 'invoices'; 

    $affiliates = DB::table('partner_affiliates')
    ->join('affiliates', 'partner_affiliates.affiliate_id', '=', 'affiliates.id')  
    ->where('partner_affiliates.partner_id', $customer->zohocust_id)  
    ->select(
        'affiliates.isp_affiliate_id',
        'affiliates.domain_name'
    )
    ->get();
    $currentSubscription = DB::table('subscriptions')
    ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
    ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
    ->select('plans.plan_price', 'plans.plan_id')
    ->first();

// Fetch plans with a higher price than the current subscribed plan
$upgradePlans = $currentSubscription 
    ? Plan::where('plan_price', '>', $currentSubscription->plan_price)->get() 
    : [];

    $plans = DB::table('plans')->select('plan_code', 'plan_name', 'plan_price')->get();
    $partnerUsers = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)->get();
  
    $companyInfo = DB::table('company_info')
    ->where('zoho_cust_id', $customer->zohocust_id)
    ->first();

    $providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->first();
    // Pass the data back to the view
    return view('customer-show', compact('customer','partnerUsers',
     'subscriptions','selectedSection', 'affiliates', 'search', 'startDate', 'endDate',
      'invoices','creditnotes', 'partnerUser','plans','upgradePlans', 'companyInfo',
      'providerData'));
}

public function filtercreditnav(Request $request)
{

    $zohocust_id = $request->input('zohocust_id');
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $perPage = $request->input('rows_to_show', 10);

    $query = DB::table('creditnotes')
        ->join('partners', 'creditnotes.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'creditnotes.*',
            'partners.company_name'
        )
        ->where('creditnotes.zoho_cust_id', $zohocust_id);

    if ($startDate) {
        $query->whereDate('credited_date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('credited_date', '<=', $endDate);
    }

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('creditnotes.creditnote_number', 'LIKE', "%{$search}%")
              ->orWhere('creditnotes.invoice_number', 'LIKE', "%{$search}%");
        });
    }

    $creditnotes = $query->paginate($perPage);

  


    $customer = Partner::where('zohocust_id', $zohocust_id)->first();
    $partnerUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
    ->whereNotNull('zoho_cpid') 
    ->get();

    $subscriptions = Subscription::where('zoho_cust_id', $zohocust_id)->get();
    $invoices = Invoice::where('zoho_cust_id', $customer->zohocust_id)->get();
   
    $affiliates = DB::table('partner_affiliates')
    ->join('affiliates', 'partner_affiliates.affiliate_id', '=', 'affiliates.id')  
    ->where('partner_affiliates.partner_id', $customer->zohocust_id)  
    ->select(
        'affiliates.isp_affiliate_id',
        'affiliates.domain_name'
    )
    ->get();
    $selectedSection = 'creditnote'; 
    $currentSubscription = DB::table('subscriptions')
    ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
    ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
    ->select('plans.plan_price', 'plans.plan_id')
    ->first();

// Fetch plans with a higher price than the current subscribed plan
$upgradePlans = $currentSubscription 
    ? Plan::where('plan_price', '>', $currentSubscription->plan_price)->get() 
    : [];
 
    $plans = DB::table('plans')->select('plan_code', 'plan_name', 'plan_price')->get();
    $partnerUsers = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)->get();

    $companyInfo = DB::table('company_info')
    ->where('zoho_cust_id', $customer->zohocust_id)
    ->first();

    $providerData = ProviderData::where('zoho_cust_id', $customer->zohocust_id)->first();

    return view('customer-show', compact('customer','partnerUsers', 'subscriptions', 
    'selectedSection', 'affiliates','invoices', 'search', 'startDate', 'endDate', 
    'creditnotes','partnerUser','plans','upgradePlans', 'companyInfo',
    'providerData'));
}

public function filterProviderDatanav(Request $request)
{
    // Retrieve input values
    $zohocust_id = $request->input('zohocust_id');
    $search = $request->input('search');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $perPage = $request->input('rows_to_show', 10);  // Default to 10 rows per page

    // Build the query for provider data
    $query = DB::table('provider_data')
        ->join('partners', 'provider_data.zoho_cust_id', '=', 'partners.zohocust_id')
        ->select(
            'provider_data.*',
            'partners.company_name'
        )
        ->where('provider_data.zoho_cust_id', $zohocust_id);

    // Apply filters for date range if provided
    if ($startDate) {
        $query->whereDate('provider_data.created_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('provider_data.created_at', '<=', $endDate);
    }

    // Apply search filter if provided
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('provider_data.file_name', 'LIKE', "%{$search}%")
              ->orWhere('provider_data.zip_count', 'LIKE', "%{$search}%");
        });
    }

    // Fetch filtered provider data with pagination
    $providerData = $query->paginate($perPage);
    \Log::info($providerData);
    $customer = Partner::where('zohocust_id', $zohocust_id)->first();
    $partnerUser = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)
        ->whereNotNull('zoho_cpid') 
        ->get();

    // Additional data (subscriptions, invoices, affiliates, etc.)
    $subscriptions = Subscription::where('zoho_cust_id', $zohocust_id)->get();
    $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();
    $invoices = Invoice::where('zoho_cust_id', $customer->zohocust_id)->get();
   
    $affiliates = DB::table('partner_affiliates')
        ->join('affiliates', 'partner_affiliates.affiliate_id', '=', 'affiliates.id')  
        ->where('partner_affiliates.partner_id', $customer->zohocust_id)  
        ->select(
            'affiliates.isp_affiliate_id',
            'affiliates.domain_name'
        )
        ->get();

    $selectedSection = 'providerdata';  // Change section name to "providerdata"
    
    // Fetch subscription plans
    $currentSubscription = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->where('subscriptions.zoho_cust_id', $customer->zohocust_id)
        ->select('plans.plan_price', 'plans.plan_id')
        ->first();

    // Fetch plans with a higher price than the current subscribed plan
    $upgradePlans = $currentSubscription 
        ? Plan::where('plan_price', '>', $currentSubscription->plan_price)->get() 
        : [];
 
    $plans = DB::table('plans')->select('plan_code', 'plan_name', 'plan_price')->get();
    $partnerUsers = PartnerUser::where('zoho_cust_id', $customer->zohocust_id)->get();
    $companyInfo = DB::table('company_info')
    ->where('zoho_cust_id', $customer->zohocust_id)
    ->first();

    return view('customer-show', compact('customer','partnerUsers', 
    'subscriptions','selectedSection', 'affiliates', 'search', 'startDate','creditnotes',
     'endDate', 'invoices', 'partnerUser','plans','upgradePlans' ,'companyInfo',
     'providerData'));
}   


public function customenterprise(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
    $zohocpid = $partneruser->zoho_cpid;
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first(); 

    $zohoCustId =  $customer->zohocust_id;

    $subscription = Subscription::where('zoho_cust_id', $zohoCustId)->first();
    $subscriptionNumber = $subscription ? $subscription->subscription_number : null;

    $openTicket = Support::where('zoho_cust_id', $zohoCustId)
    ->where('request_type', 'Custom Enterprise')
    ->where('status', 'open')
    ->first();

if ($openTicket) {
  
    return back()->withErrors('You already raised a support ticket');
}

    Support::create([
        'date' => now(),
        'request_type' => 'Custom Enterprise', 
        'message' => $request->input('message'),
        'status' => 'open',
        'zoho_cust_id' => $zohoCustId,
        'zoho_cpid' => $zohocpid, 
        'subscription_number' => $subscriptionNumber,
     
    ]);

    return redirect()->route('show.support')->with('success', 'Support ticket created successfully.');
}

public function revokeTicket(Request $request)
{

    $validated = $request->validate([
        'zoho_cust_id' => 'required|integer|exists:supports,zoho_cust_id', 
        'comment' => 'required|string|max:1000',
    ]);

    $support = Support::where('zoho_cust_id', $validated['zoho_cust_id'])
                       ->whereIn('request_type', ['Custom Support', 'Custom Enterprise'])
                      ->where('status', 'open') 
                      ->first();

    if ($support) {
        
        $support->comments = $validated['comment'];
        $support->status = 'Completed'; 
        $support->save();
    } else {
        
        Support::create([
            'zoho_cust_id' => $validated['zoho_cust_id'],
            'comments' => $validated['comment'],
            'status' => 'Completed', 
            'request_type' => 'Customer Support', 
            'date' => now()
        ]);
    }

    return redirect()->back()->with('success', 'Ticket has been updated/revoked successfully.');
}
public function inviteUser(Request $request)
{
    $validated = $request->validate([
        'first_name'   => 'required|string|max:255',
        'last_name'    => 'required|string|max:255',
        'email'        => 'required|email|max:255|unique:partner_users,email',
        'phone_number' => 'required|string|max:15',
        'zoho_cust_id' => 'required|string', 
    ]);

    $defaultPassword = Str::random(16);

    $accessToken = $this->zohoService->getAccessToken();

    $zohoResponse = Http::withHeaders([
        'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        'Content-Type'  => 'application/json',
        'X-com-zoho-subscriptions-organizationid' => config('services.zoho.zoho_org_id'),
    ])->post('https://www.zohoapis.com/billing/v1/customers/' . $validated['zoho_cust_id'] . '/contactpersons', [
        'first_name' => $validated['first_name'],
        'last_name'  => $validated['last_name'],
        'email'      => $validated['email'],
        'phone'      => $validated['phone_number'],
    ]);

    if ($zohoResponse->successful()) {
        $responseData = $zohoResponse->json();

        $contactPersonId = $responseData['contactperson']['contactperson_id'] ?? null;
        $customerId      = $responseData['contactperson']['customer_id'] ?? null;

        // Save user data into the database
        $partnerUser = PartnerUser::create([
            'first_name'       => $validated['first_name'],
            'last_name'        => $validated['last_name'],
            'email'            => $validated['email'],
            'phone_number'     => $validated['phone_number'],
            'zoho_cust_id'     => $validated['zoho_cust_id'],
            'zoho_cpid'        => $contactPersonId,
            'zoho_customer_id' => $customerId,
            'password'         => bcrypt($defaultPassword), 
        ]);

        // Prepare login URL
        $loginUrl = route('login');

        // Send email with login details
        try {
            Mail::to($partnerUser->email)->send(new CustomerInvitation($partnerUser, $defaultPassword, $loginUrl));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'User invited, but email could not be sent: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'User invited successfully. An email has been sent.');
    } else {
        $errorMessage = $zohoResponse->json('message') ?? 'Failed to invite user.';
        return redirect()->back()->withErrors(['error' => $errorMessage]);
    }
}
public function Cancellation(Request $request)
{
    $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();

    if (!$partneruser) {
        return back()->withErrors('Customer not found.');
    }
     
    $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

    if (!$customer) {
        return back()->withErrors('Customer details not found.');
    }

    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();

    if (!$subscription) {
        return back()->withErrors('Subscription not found.');
    }

    $openTicket = Support::where('zoho_cust_id', $customer->zohocust_id)
        ->where('request_type', 'Cancellation')
        ->where('status', 'open')
        ->first();

    if ($openTicket) {

        return back()->withErrors('An open cancellation request already exists.');
    }

    Support::create([
        'date' => now(),
        'request_type' => 'Cancellation', 
        'subscription_number' => $subscription->subscription_number,
        'message' => 'I would like to cancel my existing subscription (' . $subscription->subscription_number . '). Please contact me with next steps for cancellation.',
        'status' => 'open',
        'zoho_cust_id' => $customer->zohocust_id,
        'zoho_cpid' => $partneruser->zoho_cpid,
    ]);

    return redirect()->route('show.support')->with('success', 'Cancellation request submitted successfully.');
}
public function cancelSubscription(Request $request)
{
   
        $accessToken = $this->zohoService->getAccessToken();

      
        $subscriptionId = $request->input('subscription_id');
        $subscriptionNumber = $request->input('subscription_number');
        
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'X-com-zoho-subscriptions-organizationid' => config('services.zoho.zoho_org_id'),
        ])->post("https://www.zohoapis.com/billing/v1/subscriptions/{$subscriptionId}/cancel", [
            'organization_id' => config('services.zoho.zoho_org_id'),
        ]);
    
        if ($response->failed()) {
            return back()->withErrors('Failed to cancel the subscription. Please try again.');
        }

        DB::table('supports')
            ->where('subscription_number', $subscriptionNumber)
            ->where('request_type', 'Cancellation')
            ->update(['status' => 'Completed']);

        DB::table('subscriptions')
            ->where('subscription_number', $subscriptionNumber)
            ->update(['status' => 'Cancelled']);


        return redirect()->route('Support.Ticket')->with('success', 'Subscription successfully canceled!');
 
    }

    public function subscribelink(Request $request)
    {
        $plancode = $request->input('plan_id');
    
        $accessToken = $this->zohoService->getAccessToken();
    
        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return redirect()->back()->withErrors('Customer not found.');
        }
    
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();
    
        if (!$customer) {
            return redirect()->back()->withErrors('Customer not found.');
        }
    
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post(config('services.zoho.zoho_new_subscription'), [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'customer_id' => $customer->zohocust_id,
            'customer' => [
                'display_name' => $customer->customer_name,
                'email' => $partneruser->email,
            ],
            'plan' => [
                'plan_code' => $plancode
            ],
            'redirect_url' => url('thankyou')
        ]);
    
        if ($response->successful()) {
            $hostedPageData = $response->json();
            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
    
            Session::put('hostedpage_id', $hostedPageData['hostedpage']['hostedpage_id']); 
    
            $this->sendSubscriptionEmail($partneruser, $customer, $hostedPageUrl, $plancode);
    
            return redirect()->back()->with('success', 'Subscription email sent to the customer successfully.');
          } else {
            return redirect()->back()->withErrors('Error creating subscription: ' . $response->body());
        }
    }
    public function sendSubscriptionEmail($partneruser,$customer, $hostedPageUrl, $plancode)
    {

        $plan = Plan::where('plan_code', $plancode)->first();
   
        if (!$plan) {
            return back()->withErrors('Plan not found.');
        }
        
        $emailData = [
            'customer_name' => $customer->customer_name,
            'hostedPageUrl' => $hostedPageUrl,
            'plan' => $plan,
           'email' => $partneruser->email,
        ];

            Mail::to($partneruser->email)->send(new SubscriptionEmail($emailData));
    }

    public function upgradelink(Request $request)
    {
        $accessToken = $this->zohoService->getAccessToken();
    
        // Retrieve the logged-in user's email from the session
        $partnerUser = PartnerUser::where('email', Session::get('user_email'))->first();
        if (!$partnerUser) {
            return back()->withErrors('Customer not found.');
        }
    
        // Fetch customer and subscription details
        $customer = Partner::where('zohocust_id', $partnerUser->zoho_cust_id)->first();
        if (!$customer) {
            return back()->withErrors('Customer not found.');
        }
    
        $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
        if (!$subscription) {
            return back()->withErrors('Subscription not found.');
        }
    
        // Get the plan code from the dropdown
        $planCode = $request->input('plan_code');
        if (!$planCode) {
            return back()->withErrors('Plan code is required.');
        }
    
        // Make the API call to upgrade the subscription
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post(config('services.zoho.zoho_upgrade_subscription'), [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'subscription_id' => $subscription->subscription_id,
            'plan' => [
                'plan_code' => $planCode
            ],
            'redirect_url' => url('thanks')
        ]);
    
        if ($response->successful()) {
            $hostedPageData = $response->json();
            $hostedPageUrl = $hostedPageData['hostedpage']['url'];
            $hostedPageId = $hostedPageData['hostedpage']['hostedpage_id'];
    
           
            Session::put('hostedpage_id', $hostedPageId);
    
           
            $this->sendupgradeEmail($partnerUser, $customer, $hostedPageUrl, $planCode);
    
            return redirect()->back()->with('success', 'Subscription upgraded successfully. Please check your email to complete the process.');
        } else {
      
            return redirect()->back()->withErrors('Failed to upgrade subscription: ' . $response->body());
        }
    }

    public function sendupgradeEmail($partnerUser, $customer, $hostedPageUrl, $planCode)
{
    $plan = Plan::where('plan_code', $planCode)->first();
   
    if (!$plan) {
        throw new \Exception('Plan not found.');
    }
        
    $emailData = [
        'customer_name' => $customer->customer_name,
        'hostedPageUrl' => $hostedPageUrl,
        'plan' => $plan,
        'email' => $partnerUser->email,
        'is_upgrade' => 'yes',
    ];

    // Send the email using the Mailable
    Mail::to($partnerUser->email)->send(new UpgradeEmail($emailData));
}
public function view($id)
{
    $plan = Plan::find($id);

    if (!$plan) {
        abort(404, 'Plan not found');
    }

    $feature = Feature::where('plan_code', $plan->plan_code)->first();

    if (!$feature) {
        $feature = (object)[
            'features_json' => json_encode([]), // Ensure this is a JSON string
        ];
    }

    // Ensure features_json is a string before decoding
    $featuresJson = is_string($feature->features_json) ? $feature->features_json : json_encode($feature->features_json);

    // Decode the JSON string
    $features = json_decode($featuresJson, true);

    return view('plan-features', compact('plan', 'features'));
}


public function updateFeatures(Request $request)
    {
     
        $featuresJson = [
            'Update Logo' => $request->has('update_logo') ? 'Yes' : 'No',
            'Custom URL' => $request->has('custom_url') ? 'Yes' : 'No',
            'Zip Code Availability Updates' => $request->has('zip_code_updates') ? 'Yes' : 'No',
            'Data Updates' => $request->has('data_updates') ? 'Yes' : 'No',
            'Self Service Portal Access' => $request->has('self_service_access') ? 'Yes' : 'No',
            'Account Management Support' => $request->has('account_support') ? 'Yes' : 'No',
            'Reporting' => $request->input('reporting'),
            'Maximum Allowed Clicks' => $request->input('max_clicks'),
            'Maximum Click Monthly Add-on' => $request->input('click_addon'),
        ];

        $feature = Feature::firstOrCreate(
            ['plan_code' => $request->input('plan_code')], // Match or create based on plan_code
            ['features_json' => json_encode($featuresJson)] // Initial value if new
        );

    
        if (!$feature->wasRecentlyCreated) {
            $feature->update(['features_json' => $featuresJson]);
        }

        return redirect()->back()->with('success', 'Features updated successfully!');
    }

    public function aduploadCsv(Request $request)
{
    try {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if (!$request->hasFile('csv_file')) {
            return back()->withErrors('No file received.');
        }

        $file = $request->file('csv_file');
        $fileName = $file->getClientOriginalName(); 
        $fileSize = $file->getSize();
        $filePath = $file->storeAs('uploads/csv_files', $fileName, 'public');

        $partneruser = PartnerUser::where('email', Session::get('user_email'))->first();
    
        if (!$partneruser) {
            return back()->withErrors('Customer not found.');
        }
        
        $customer = Partner::where('zohocust_id', $partneruser->zoho_cust_id)->first();

        $lineCount = 0;
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            // Skip the header row
            $headerSkipped = false;
            while (($data = fgetcsv($handle)) !== false) {
                if (!$headerSkipped) {
                    $headerSkipped = true; 
                    continue;
                }

                if (array_filter($data)) {
                    $lineCount++;
                }
            }
            fclose($handle);
        }
    
        $providerData = new ProviderData();
        $providerData->file_name = $fileName;
        $providerData->file_size =  $fileSize;
        $providerData->url = $filePath;
        $providerData->zoho_cust_id = $customer->zohocust_id;
        $providerData->uploaded_by = $customer->customer_name;
        $providerData->zip_count = $lineCount;
        $providerData->save();

        if (CompanyInfo::where('zoho_cust_id', $customer->zohocust_id)->exists()) {
            $customer->first_login = false;
            $customer->save();
        }

        return back()->with('success', 'File uploaded successfully!');
    } catch (\Exception $e) {
        Log::error('CSV Upload Error', ['error' => $e->getMessage()]);
        return back()->withErrors('An unexpected error occurred.');
    }
}


public function resendInvite(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email|exists:partner_users,email',
    ]);

    $partnerUser = PartnerUser::where('email', $validated['email'])->first();

    if (!$partnerUser) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $loginUrl = route('login');

    $password = Str::random(16);  

    $partnerUser->password = bcrypt($password); 
    $partnerUser->save();

    try {
   
        Mail::to($partnerUser->email)->send(new CustomerInvitation($partnerUser, $password, $loginUrl));

        return back()->with('success', 'File uploaded successfully!');
    } catch (\Exception $e) {
        return back()->withErrors('An unexpected error occurred.');
    }
}


}




 
