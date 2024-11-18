<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Affiliate;

class AffiliateController extends Controller
{
    public function affiliate(Request $request)
    {
       
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $search = $request->get('search');
        $show = $request->get('show', 10); // Default to 10 entries per page
    
        // Build the query
        $query = Affiliate::query();
    
        // Filter by start and end date
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
    
        // Filter by search term
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('isp_affiliate_id', 'like', '%' . $search . '%')
                  ->orWhere('domain_name', 'like', '%' . $search . '%');
            });
        }
    
        // Paginate results
        $affiliates = $query->paginate($show);
    
        // Return the view with the data
        return view('affiliate', compact('affiliates'));
    }
    public function affiliatestore(Request $request)
{
    // Validate the input data
    $request->validate([
        'affiliate_id' => 'required|unique:affiliates,isp_affiliate_id|max:255',
        'domain_name' => 'required|string|max:255',
    ], [
        'affiliate_id.unique' => 'The Affiliate ID already exists. Please use a different one.'
    ]);

    // Create a new affiliate record
    Affiliate::create([
        'isp_affiliate_id' => $request->affiliate_id,
        'domain_name' => $request->domain_name,
    ]);

    // Redirect with a success message
    return redirect()->route('affiliates.index')->with('success', 'Affiliate added successfully!');
}
}
