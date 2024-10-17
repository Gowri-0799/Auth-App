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
        dd( $zohoPlanId);
        $plan->plan_id = $zohoPlanId;
      dd($plan);
         $plan->save();
        }
        else{
            foreach ($validated as $key => $value) {
                if (!is_null($value)) {
                    $existingPlan->$key = $value;
                }
            }
        }
            return redirect()->back()->with('success', 'Plan created successfully and sent to Zoho!');
       
            // Handle errors
        
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
            'Content-Type'  => 'application/json'
        ])->post('https://www.zohoapis.com/billing/v1/plans', [
            'plan_code' => $planCode,
            'name' => $plan->plan_name,
            'recurring_price' => $plan->plan_price,
            'product_id' => '5437538000000088251',
            'interval_unit' => 'months', // You can customize this according to your plan
            'interval' => 1,
            'pricing_scheme' => 'flat',
        ]);
dd( $response);
        // Check if the response is successful
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData['plan']['plan_code']['plan_id']; // Return Zoho's plan ID
        } else {
            throw new \Exception('Zoho API error: ' . $response->body());
        }
    }
}
