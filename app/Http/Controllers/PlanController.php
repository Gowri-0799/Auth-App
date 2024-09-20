<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ZohoService;

class PlanController extends Controller
{
    protected $zohoService;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
    }

    public function create()
    {
        // Return the view where the form is located
        return view('plans.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        // Step 1: Create the plan in the MySQL database
        $plan = Plan::create([
            'plan_name' => $validated['name'],
            'plan_price' => $validated['price'],
        ]);

        // Step 2: Send the plan to Zoho API
        try {
            $zohoPlanId = $this->createPlanInZoho($plan);

            // Step 3: Update the MySQL plan with Zoho's plan ID
            $plan->zoho_plan_id = $zohoPlanId;
            $plan->save();

            return redirect()->back()->with('success', 'Plan created successfully and sent to Zoho!');
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->withErrors('Failed to create plan in Zoho: ' . $e->getMessage());
        }
    }

    private function createPlanInZoho($plan)
    {
        // Get the Zoho access token
        $accessToken = $this->zohoService->getAccessToken();

        // Define the plan_code (Zoho requires this to be a unique identifier)
        $planCode = 'plan_' . time(); // You can generate this dynamically or have custom logic

        // Make a POST request to Zoho API to create the plan
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->post('https://www.zohoapis.com/billing/v1/plans', [
            'name' => $plan->plan_name,
            'plan_code' => $planCode,  // Pass the generated plan_code here
            'recurring_price' => $plan->plan_price,
            'product_id' => '5437538000000088251',
            'interval_unit' => 'months', // You can customize this according to your plan
            'interval' => 1,
            'pricing_scheme' => 'flat',
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['plan']['plan_code']; // Return Zoho's plan ID
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }
}
