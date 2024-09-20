<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OauthToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class ZohoService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $organizationId;
    protected $refreshToken;

    public function __construct()
    {
        $this->clientId = config('services.zoho.client_id');
        $this->clientSecret = config('services.zoho.client_secret');
        $this->redirectUri = config('services.zoho.redirect_uri');
        $this->refreshToken = config('services.zoho.refresh_token');
        $this->organizationId=config('services.zoho.zoho_org_id');
    }

    public function getAccessToken()
    {
        // run thr below code for first acces token 
    //    return $this->refreshAccessToken($this->refreshToken);
        // Get the token from the database
        $token = OauthToken::first();

        if (!$token) {
            // No token found, you need to start the OAuth flow (for first-time login)
            return $this->initiateOAuth();
        }

        // Check if the token is expired
        if (now()->greaterThanOrEqualTo($token->expires_at)) {
            // Token expired, refresh it using the refresh_token
            return $this->refreshAccessToken($token->refresh_token);
        }

        // Return the valid access token
        return $token->access_token;
    }

    public function initiateOAuth()
    {
        // Build the URL for Zoho's authorization page
        $authUrl = 'https://accounts.zoho.com/oauth/v2/auth?client_id=' . config('services.zoho.client_id') .
            '&response_type=code' .
            '&redirect_uri=' . urlencode(config('services.zoho.redirect_uri')) .
            '&scope=ZohoBilling.fullaccess.all';

        // Redirect the user to the OAuth page
        return redirect($authUrl);
    }

    public function handleOAuthCallback($authorizationCode)
    {
        // Exchange authorization code for access and refresh tokens
        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Store the tokens in the database
            OauthToken::create([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            return $data['access_token'];
        } else {
            throw new \Exception('Failed to obtain access token.');
        }
    }

    public function refreshAccessToken($refreshToken)
    {
        // Make a POST request to Zoho's API to refresh the token
        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'refresh_token' => config('services.zoho.refresh_token'),
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'grant_type' => 'refresh_token',
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Check if the 'access_token' key exists in the response
            if (isset($data['access_token'])) {
                // Update the token in the database
                OauthToken::updateOrCreate(
                    ['id' => 1], // Token identifier
                    [
                        'refresh_token' => $refreshToken,
                        'access_token' => $data['access_token'],
                        'expires_at' => now()->addSeconds($data['expires_in']), // Set new expiration time
                    ]
                );

                return $data['access_token'];
            } else {
                // If 'access_token' is missing, log the response for debugging
                throw new \Exception('Access token not found in the response: ' . json_encode($data));
            }
        } else {
            throw new \Exception('Failed to refresh access token: ' . $response->body());
        }
    }

    public function getZohoPlans()
    {
        // Get the access token
        $accessToken = $this->getAccessToken();

        // Make the API request with the token
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->get('https://www.zohoapis.com/billing/v1/plans');

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // Return the API data
        } else {
            // If the request fails (e.g., 401 Unauthorized), handle the error
            throw new \Exception('Failed to fetch Zoho plans: ' . $response->body());
        }
    }

    public function getZohoCustomers()
    {
        // Get the access token
        $accessToken = $this->getAccessToken();

        // Make the API request with the token
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->get('https://www.zohoapis.com/billing/v1/customers');

        // Handle the response
        if ($response->successful()) {
            return $response->json(); // Return the API data
        } else {
            // If the request fails (e.g., 401 Unauthorized), handle the error
            throw new \Exception('Failed to fetch Zoho customers: ' . $response->body());
        }
    }


    
}

