<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HealthGuardController extends Controller
{
    public function calculatePremium($sumInsured, $tenure, $age, $userData)
    {
        // Prepare the request payload
        $payload = [
            "age" => $age,
            "tenure" => $tenure,
            "gender" => $userData['gender'],
            "sum_insured" => $sumInsured,
        ];

        // Make the API call
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('http://healthguard/premium', $payload);

        // Print the response
        if ($response->successful()) {
            $responseData = $response->json(); // Get the JSON response
            $result = [
                'net_premium' => $responseData['data']['net_premium'] ?? null,
                'total_premium' => $responseData['data']['total_premium'] ?? null,
                'gst' => $responseData['data']['gst'] ?? null,
            ];

            return $result;
        } else {
            // Handle errors, e.g., log them or return a specific error message
            return response()->json(['error' => 'Failed to retrieve premium data'], $response->status());
        }
    }
}
