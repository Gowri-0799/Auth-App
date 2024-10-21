<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
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

class ZohoController extends Controller
{
    protected $zohoService;
    
    protected $plan;
    protected $customer;
    protected $subscription;
    protected $invoice;
    protected $payment;
    protected $Creditnote;
    protected $support;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
        $this->plan = new Plan();
        $this->customer = new Customer();
        $this->subscription = new Subscription();
        $this->invoice =new Invoice();
        $this->payment =new Payment();
        $this->creditnote =new Creditnote();
        $this->support=new Support();
    }

    public function getAllPlans()
    {
        
            $plans = $this->zohoService->getZohoPlans();
    
            // Check if 'plans' is nested inside another key (adjust based on API response structure)
            if (isset($plans['plans'])) {

                Plan::truncate();

                foreach ($plans['plans'] as $plan) {

                    // Delete existing plan with the same name
                    //Plan::where('plan_name', $plan['name'])->delete();

                    $this->plan = new Plan(); // Create a new instance for each plan

                    $this->plan->plan_name = $plan['name'] ?? null; // Set default null if key doesn't exist
                    $this->plan->plan_price = $plan['recurring_price'] ?? null; // Set default null if key doesn't exist
                    $this->plan->plan_code=$plan['plan_code']??null;
                    $this->plan->plan_id=$plan['plan_id']??null;
                    $this->plan->save(); // Save the plan to the database
                }
            }
       // }

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
       
            // Handle errors
        
    }

    private function createPlanInZoho($plan)
    {
        // Get the Zoho access token
        $accessToken = $this->zohoService->getAccessToken();

        // Define the plan_code (Zoho requires this to be a unique identifier)
      // You can generate this dynamically or have custom logic

        // Make a POST request to Zoho API to create the plan
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json',
            'organization_id' => config('services.zoho.zoho_org_id'),
        ])->post('https://www.zohoapis.com/billing/v1/plans', [
            'plan_code' => $plan->plan_code,
            'name' => $plan->plan_name,
            'recurring_price' => (float) $plan->plan_price, // Ensure price is in correct format (float)
            'product_id' => '5437538000000088227', // Check if this product_id is correct
            'interval_unit' => 'months', // Confirm this is the right value
            'interval' => 1, // Confirm the correct interval
        ]);
        
        // Debugging: Show response details
       

        // Check if the response is successful
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['plan']['plan_id']; // Return Zoho's plan ID
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
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

                    Customer::updateOrCreate(
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
  
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    $payments = Payment::where('zoho_cust_id', $customer->zohocust_id)->get();

    return view('profile', [
        'customer' => $customer,
        'payments' => $payments,
    ]);
   }


    function cust()
    {
        $response['customers'] = $this->customer->all();
        return view("cust")->with($response);
    }

    

    function showplan()
    {
        $customer = Customer::where('customer_email', Session::get('user_email'))->first();

        // Check if the customer exists
        if (!$customer) {
            return back()->withErrors('Customer not found.');
        }
    
        // Fetch subscriptions for the customer
        $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->get();
    
        // Fetch all plans ordered by price
        $plans = Plan::orderBy('plan_price', 'asc')->get();
    
        // Pass both subscriptions and plans to the view
        return view('sub', compact('subscriptions', 'plans'));
    }
    function showsubscription()
    {
        $subscriptions = DB::table('subscriptions')
        ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
        ->join('customers', 'subscriptions.zoho_cust_id', '=', 'customers.zohocust_id')
        ->select(
            'subscriptions.subscription_id',
            'subscriptions.subscription_number',
            'customers.company_name',
            'plans.plan_name',
            'plans.plan_price',
            'subscriptions.start_date',
            'subscriptions.next_billing_at',
            'subscriptions.status'
        )
        ->get();

       
    return view('subscription', compact('subscriptions'));
    }
    public function filterSubscriptions(Request $request)
    {
        // Get the search term and dates
        $search = $request->input('search');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $perPage = $request->input('show', 10); // Number of entries to show
    
        // Start the query for filtering subscriptions
        $query = DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.plan_id')
            ->join('customers', 'subscriptions.zoho_cust_id', '=', 'customers.zohocust_id')
            ->select(
                'subscriptions.subscription_id',
                'subscriptions.subscription_number',
                'customers.company_name',
                'plans.plan_name',
                'plans.plan_price',
                'subscriptions.start_date',
                'subscriptions.next_billing_at',
                'subscriptions.status'
            );
    
        // Apply the date filters if they exist
        if ($startDate) {
            $query->whereDate('subscriptions.start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('subscriptions.start_date', '<=', $endDate);
        }
    
        // Apply the search filter on the plan name
        if ($search) {
            $query->where('plans.plan_name', 'LIKE', "%{$search}%");
        }
    
        // Paginate the results
        $subscriptions = $query->paginate($perPage);
    
        // Pass the data back to the view
        return view('subscription', compact('subscriptions', 'search', 'startDate', 'endDate'));
    }
    
    public function showinvoice()
    {
        // Execute the query to fetch invoices along with related plan and customer data
        $invoices = DB::table('invoices')
            ->join('customers', 'invoices.zoho_cust_id', '=', 'customers.zohocust_id')
            ->select(
                'invoices.*',
                'customers.company_name',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) AS plan_name"), // Extracting plan name from JSON
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].price')) AS plan_price"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_details, '$[0].payment_mode')) AS payment_mode")
            )
            ->get(); // Retrieve the data
    
        // Pass the retrieved invoices to the view
        return view('invoice', compact('invoices'));
    }
    
    public function filteradInvoices(Request $request)
    {
        // Get the search term and date range from the request
        $search = $request->input('search');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $perPage = $request->input('show', 10); // Default entries to show
    
        // Start the query for filtering invoices
        $query = DB::table('invoices')
            ->join('customers', 'invoices.zoho_cust_id', '=', 'customers.zohocust_id')
            ->select(
                'invoices.*',
                'customers.company_name',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) AS plan_name"), // Extracting plan name from JSON
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].price')) AS plan_price"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(payment_details, '$[0].payment_mode')) AS payment_mode")
            );
    
        // Apply date filters if they exist
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
        //AccessToken::create(['access_token' => $accessToken]);
        return back()->with('accessToken', $accessToken);
    }

    public function display(){
        return view("custdetail");
    }

    public function store(Request $request)
{
    // Validate the request data, including checking for unique email
    $validatedData = $request->validate([
        'first_name' => 'nullable',
        'last_name' => 'nullable',
        'customer_email' => 'required|email|unique:customers,customer_email', // Added unique rule
        'company_name' => 'required',
        'billing_street' => 'nullable|string',
        'billing_city' => 'nullable|string',
        'billing_state' => 'nullable|string',
        'billing_country' => 'nullable|string',
        'billing_zip' => 'nullable|string',
    ], [
        // Custom error message for existing email
        'customer_email.unique' => 'The email ID already exists.',
    ]);
    $fullName = trim($validatedData['first_name'] . ' ' . $validatedData['last_name']);
    // No need to check for existing customer, validation handles that
    $defaultPassword = Hash::make('soxco123');

    $customer = Customer::create([
        'customer_name' =>  $fullName,
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'customer_email' => $validatedData['customer_email'],
        'company_name' => $validatedData['company_name'],
        'password' => $defaultPassword,
        // Billing Address
        'billing_attention' => $fullName,
        'billing_street' => $validatedData['billing_street'],
        'billing_city' => $validatedData['billing_city'],
        'billing_state' => $validatedData['billing_state'],
        'billing_country' => $validatedData['billing_country'],
        'billing_zip' => $validatedData['billing_zip'],
        // Shipping Address
        'shipping_attention' =>$fullName,
        'shipping_street' => $validatedData['billing_street'],
        'shipping_city' => $validatedData['billing_city'],
        'shipping_state' => $validatedData['billing_state'],
        'shipping_country' => $validatedData['billing_country'],
        'shipping_zip' => $validatedData['billing_zip'],
    ]);

    // Send the new customer to Zoho
    $zohoResponse = $this->createCustomerInZoho($customer);
    $customer->zohocust_id = $zohoResponse;
    $customer->save();

    return redirect(route('cust'))->with('success', 'Customer added successfully!');
}

  

    private function createCustomerInZoho($customer)
    {
        $accessToken = $this->zohoService->getAccessToken();


        // Call the Zoho API to create the customer
        $response = Http::withHeaders([ 'Authorization' => 'Zoho-oauthtoken ' .$accessToken
        ])->post('https://www.zohoapis.com/billing/v1/customers', [
            'organization_id' => config('services.zoho.zoho_org_id'),
            // 'JSONString' => json_encode($customerData)
            'display_name' =>  $customer->customer_name, 
            // 'customer_id' =>    $custCode,   
            'first_name' =>  $customer->first_name,
            'last_name' =>  $customer->last_name,
            'email' =>  $customer->customer_email,
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
               
            ]
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['customer']['customer_id']; // Return Zoho's plan ID
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }

    public function subscribe($planId)
    {
        $accessToken = $this->zohoService->getAccessToken();

        $customer = Customer::where('customer_email', Session::get('user_email'))->first();

        if (!$customer) {
            return back()->withErrors('Customer not found.');
        }


        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type'  => 'application/json' // Ensure the Content-Type is JSON
        ])->post('https://www.zohoapis.com/billing/v1/hostedpages/newsubscription', [
            'organization_id' => config('services.zoho.zoho_org_id'),
            'customer_id'  => $customer->zohocust_id, // Zoho customer ID
            'customer' => [
                'display_name' => $customer->customer_name,
                'email'        => $customer->customer_email,
            ],
            'plan' => [
                    'plan_code' => $planId // Plan code from the form
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
                return view('thankyou');
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
         
                $this->upgradeSubscriptionData($hostedPageData); // Pass the JSON data to store it
                return view('thanks');
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
            'status' => $data['status'],
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
            'status' => $data['status'],
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
        return redirect()->route('thankyou')->with('success', 'Subscription successfully completed!');
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
        $customer = Customer::where('zohocust_id', $zohocust_id)->firstOrFail();
    
        // Return the edit view and pass the customer object
        return view('edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Find the customer by ID or fail if not found
        $customer = Customer::findOrFail($id);
    
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
    // Assuming you have a session or auth method to get the logged-in customer's email
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Fetch subscriptions for the customer
    $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
    
    // Check if subscriptions exist and fetch corresponding plan details
    $plans = null;
    if ($subscriptions) {
        $plans = Plan::where('plan_id', $subscriptions->plan_id)->first();
    }

    // Get downgrade plans if subscription exists
    $downgradePlans = $plans ? Plan::where('plan_price', '<', $plans->plan_price)->get() : [];

    return view('customerSubscriptions', compact('subscriptions', 'plans', 'downgradePlans'));
}


public function showCustomerInvoices()
{
    // Get the logged-in customer's email from the session
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Fetch invoices for the customer
    $invoices = Invoice::where('zoho_cust_id', $customer->zohocust_id)->get();

    // Fetch the customer's subscription if it exists
    $subscriptions = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();

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
        $customer = Customer::where('zohocust_id', $id)->firstOrFail();
    
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
        ])->post('https://www.zohoapis.com/billing/v1/hostedpages/addpaymentmethod', [
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
        $accessToken = $this->zohoService->getAccessToken();
        $customer = Customer::where('customer_email', Session::get('user_email'))->first();
    
        if (!$customer) {
            return back()->withErrors('Customer not found.');
        }
    
        $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
  
        if (!$subscription) {
            return back()->withErrors('Subscription not found.');
        }
    
        $planId = $request->input('plan_code');
    
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post('https://www.zohoapis.com/billing/v1/hostedpages/updatesubscription', [
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
           'status' => $data['status'],
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
           'status' => $data['status'],
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
        return redirect()->route('thanks')->with('success', 'Subscription successfully completed!');
    }


    public function filterInvoices(Request $request)
{
    // Get the search term and dates
    $search = $request->input('search');
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $perPage = $request->input('show', 10); // Number of entries to show

    // Start the query for filtering invoices
    $query = DB::table('invoices');

    // Apply the date filters if they exist
    if ($startDate) {
        $query->whereDate('invoice_date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('invoice_date', '<=', $endDate);
    }

    // Apply the search filter on the plan name within the JSON field 'invoice_items'
    if ($search) {
        $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(invoice_items, '$[0].code')) LIKE ?", ["%{$search}%"]);
    }

    // Paginate the results
    $invoices = $query->paginate($perPage);

    // Pass the data back to the view
    return view('customerinvoices', compact('invoices', 'search', 'startDate', 'endDate'));
}

public function showCustomerCredits()
{
    // Get the logged-in customer's email from the session
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Fetch credit notes for the customer
    $creditnotes = Creditnote::where('zoho_cust_id', $customer->zohocust_id)->get();
    
    // Check if there are any credit notes
    if ($creditnotes->isEmpty()) {
        // No credit notes found
        $customers = null;
    } else {
        // Fetch customer details based on the first credit note
        $customers = Customer::where('zohocust_id', $creditnotes->first()->zoho_cust_id)->first();
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
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

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
    $customers = Customer::where('zohocust_id', $customer->zohocust_id)->first();
    // Pass filtered credit notes and customer details to the view
    return view('creditnotes', compact('creditnotes', 'customers'));
}
public function showCustomerSupport(Request $request)
{
    // Get the logged-in customer's email from the session
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    // Check if the customer exists
    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Start building the query for support tickets
    $supportsQuery = Support::where('zoho_cust_id', $customer->zohocust_id);

    // Apply date filters if provided
    if ($request->filled('startDate')) {
        $supportsQuery->whereDate('date', '>=', $request->startDate);
    }

    if ($request->filled('endDate')) {
        $supportsQuery->whereDate('date', '<=', $request->endDate);
    }

    // Apply search filter for request_type if provided
    if ($request->filled('search')) {
        $supportsQuery->where('request_type', 'like', '%' . $request->search . '%');
    }

    // Paginate results based on user input, default is 10
    $supports = $supportsQuery->paginate($request->input('show', 10));

    // Pass the customer and supports data to the view
    return view('support', compact('supports', 'customer'));
}




public function ticketstore(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $customer = Customer::where('customer_email', Session::get('user_email'))->first();
    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
    $zohoCustId =  $customer->zohocust_id;
    
    $subscriptionNumber =  $subscription->subscription_number;

    // Store the support ticket
    Support::create([
        'date' => now(),
        'request_type' => 'Custom', // You can set this dynamically if needed
        'subscription_number' => $subscriptionNumber,
        'message' => $request->input('message'),
        'status' => 'open',
        'zoho_cust_id' => $zohoCustId,
        'zoho_cpid' => $zohoCustId, // Adjust as needed
     
    ]);

    // Redirect back to the support page with success message
    return redirect()->route('show.support')->with('success', 'Support ticket created successfully.');
}

public function downgrade(Request $request)
{
    // Validate the request to ensure a plan is selected
    $request->validate([
        'plan_id' => 'required|exists:plans,plan_id',
    ]);
   
    // Get the logged-in customer
    $customer = Customer::where('customer_email', Session::get('user_email'))->first();

    if (!$customer) {
        return back()->withErrors('Customer not found.');
    }

    // Fetch the customer's subscription
    $subscription = Subscription::where('zoho_cust_id', $customer->zohocust_id)->first();
  
    if (!$subscription) {
        return back()->withErrors('Subscription not found.');
    }

    $selectedPlan = Plan::where('plan_id', $request->plan_id)->first();
  
    if (!$selectedPlan) {
        return back()->withErrors('Plan not found.');
    }

    // Create the downgrade support ticket
    Support::create([
        'date' => now(),
        'request_type' => 'Downgrade', // Set the request type to Downgrade
        'subscription_number' => $subscription->subscription_number,
        'message' => 'I would like to downgrade my subscription to the ' . $selectedPlan->plan_name . '. Please contact me with steps to downgrade.',        'status' => 'open',
        'zoho_cust_id' => $customer->zohocust_id,
        'zoho_cpid' => $customer->zohocust_id, // Adjust as needed
    ]);

    // Redirect back with a success message
    return redirect()->route('show.support')->with('success', 'Downgrade request submitted successfully.');
}

public function supportticket()
{
    // Join the supports table with the customers table to get company name
    $supports = DB::table('supports')
        ->join('customers', 'supports.zoho_cust_id', '=', 'customers.zohocust_id')
        ->select(
            'supports.*', // All columns from the supports table
            'customers.company_name' // Company name from the customers table
        )
        ->get(); // Retrieve the data

    // Pass the data to the view
    return view('supportticket', compact('supports'));
}

public function supportticketfilter(Request $request)
{
    // Get the filter values from the request
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $search = $request->input('search');
    $show = $request->input('show', 10); // Default to showing 10 entries if not provided

    // Base query for joining the supports and customers tables
    $query = DB::table('supports')
        ->join('customers', 'supports.zoho_cust_id', '=', 'customers.zohocust_id')
        ->select(
            'supports.*', // All columns from the supports table
            'customers.company_name' // Company name from the customers table
        );

    // Apply filters if they are provided
    if ($startDate) {
        $query->whereDate('supports.date', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('supports.date', '<=', $endDate);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('supports.subscription_number', 'LIKE', "%{$search}%")
              ->orWhere('customers.company_name', 'LIKE', "%{$search}%");
             
        });
    }

    // Get the results with pagination based on the 'show' value (for how many entries to display per page)
    $supports = $query->paginate($show);

    // Pass the filtered data to the view
    return view('supportticket', compact('supports'));
}


}




 
