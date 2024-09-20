<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Customer;
use App\Services\ZohoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class ZohoController extends Controller
{
    protected $zohoService;
    protected $plan;
    protected $customer;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
        $this->plan = new Plan();
        $this->customer = new Customer();
    }

    public function fetchPlans()
    {
        $accessToken = $this->zohoService->getAccessToken();

        // Make the API request using the access token
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken
        ])->get('https://www.zohoapis.com/billing/v1/plans');

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Failed to fetch plans'], 500);
        }
    }


    public function getAllPlans()
    {
        //$accessToken = $this->zohoService->getAccessToken();
        //$response = Http::withHeaders(['Authorization' => 'Zoho-oauthtoken ' . $accessToken])->get('https://www.zohoapis.com/billing/v1/plans');
        /*$response = $this->zohoService->getZohoPlans();

        if ($response->successful()) {
            // Zoho API responses might have a 'data' key, adjust based on the API documentation
            $plans = $response->json();*/ // Laravel's built-in method to decode JSON response
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
                     
                    $this->plan->save(); // Save the plan to the database
                }
            }
       // }

        return redirect(route("plan"));
    }
    public function getAllCustomers()
    {

            $customers = $this->zohoService->getZohoCustomers();
            // Check if 'plans' is nested inside another key (adjust based on API response structure)
            if (isset($customers['customers'])) {

                customer::truncate();

                foreach ($customers['customers'] as $customer) {

                    // Delete existing plan with the same name
                    //Plan::where('plan_name', $plan['name'])->delete();

                    $this->customer = new Customer(); // Create a new instance for each plan

                    $this->customer->customer_name = $customer['display_name'] ?? null; // Set default null if key doesn't exist
                    $this->customer->customer_email = $customer['email'] ?? null; // Set default null if key doesn't exist
                    $this->customer->zohocust_id = $customer['customer_id'] ?? null; // Set default null if key doesn't exist

                    $this->customer->save(); // Save the plan to the database
                }
            }

       // }

        return redirect(route("cust"));
    }

    function cust()
    {
        $response['customers'] = $this->customer->all();
        return view("cust")->with($response);
    }

    function plan()
    {
        $response['plans'] = $this->plan->all();
        return view("plan")->with($response);
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
        // Validate the request data
        $validatedData = $request->validate([
            'Customer_name' => 'required',
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'Customer_email' => 'required|email',
        ]);


        // // Store the data in the customers table
        // Customer::create([
        //     'Customer_name' => $validatedData['Customer_name'],
        //     'first_name' => $validatedData['first_name'] ?? null,
        //     'last_name' => $validatedData['last_name'] ?? null,
        //     'Customer_email' => $validatedData['Customer_email'],
        // ]);

        // // Redirect back or to another page
        // return redirect()->back()->with('success', 'Customer added successfully!');
      
        $customer = Customer::create([
            'Customer_name' => $validatedData['Customer_name'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'Customer_email' => $validatedData['Customer_email']
        ]);
     
        $zohoResponse = $this->createCustomerInZoho($customer);

       
        $customer->zohocust_id = $zohoResponse;
        $customer->save();
         
        return redirect(route('cust'))->with('success', 'Customer created successfully and sent to Zoho!');
    } 
  

    private function createCustomerInZoho($customer)
    {
        $accessToken = $this->zohoService->getAccessToken();

        // $custCode = 'cust_' . time();

        // $customerData = [
        //     'display_name' =>  $customer->Customer_name,           
        //     'first_name' =>  $customer->first_name,
        //     'last_name' =>  $customer->last_name,
        //     'email' =>  $customer->Customer_email,
 
        // ];
      

        // Call the Zoho API to create the customer
        $response = Http::withHeaders([ 'Authorization' => 'Zoho-oauthtoken ' .$accessToken
        ])->post('https://www.zohoapis.com/billing/v1/customers', [
            'organization_id' => config('services.zoho.zoho_org_id'),
            // 'JSONString' => json_encode($customerData)
            'display_name' =>  $customer->Customer_name, 
            // 'customer_id' =>    $custCode,   
            'first_name' =>  $customer->first_name,
            'last_name' =>  $customer->last_name,
            'email' =>  $customer->Customer_email,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['customer']['customer_id']; // Return Zoho's plan ID
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }
  

    }
 
