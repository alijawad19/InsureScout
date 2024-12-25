<?php

namespace App\Http\Controllers;

use App\Models\PaymentData;
use App\Models\PremiumData;
use App\Models\ProposalData;
use App\Models\ProposalForm;
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

    public function createProposal($enqId)
    {
        $premium_data = PremiumData::find($enqId);
        $proposal_form_data = ProposalForm::where('enqId', $enqId)->first();
        $proposal_data = ProposalData::where('enqId', $enqId)->first();

        // Prepare the request payload
        $payload = [
            "name" => $premium_data->name,
            "email" => $premium_data->email,
            "address" => $proposal_form_data->address,
            "tenure" => $proposal_data->tenure,
            "gender" => $premium_data->gender,
            "sum_insured" => $proposal_data->sum_insured,
            "pincode" => $premium_data->pincode,
            "contact" => $premium_data->contact,
            "dob" => $premium_data->dob,
            "nomineeDetails" => [
                "name" => $proposal_form_data->nominee_name,
                "relation" => ucfirst($proposal_form_data->nominee_relation),
                "dob" => $proposal_form_data->nominee_dob,
                "contact" => $proposal_form_data->nominee_contact,
            ]
        ];

        // Make the API call
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('http://healthguard/proposal', $payload);

        // Print the response
        if ($response->successful()) {
            $responseData = $response->json(); // Get the JSON response
            $applicationId = $responseData['data']['application_id'];
            $totalPremium = $responseData['data']['total_premium'];
            
            // Construct response_url dynamically
            $responseUrl = url('/payment-confirmation') . '?' . http_build_query([
                'enqId' => $enqId,
                'success' => 'true',
                'application_id' => $applicationId
            ]);

            $paymentLink = "http://healthguard/payment?application_id=$applicationId&total_premium=$totalPremium&response_url=" . urlencode($responseUrl);

            $paymentLinkResponse = Http::get($paymentLink);

            if ($paymentLinkResponse->successful()) {
                $paymentLinkData = $paymentLinkResponse->json();
                $paymentUrl = $paymentLinkData['data']['payment_url'];

                PaymentData::updateOrCreate(
                    ['enqId' => $enqId],
                    [
                        'application_id' => $applicationId,
                        'payment_status' => '0',
                    ]
                );

                $result = [
                    'net_premium' => $responseData['data']['net_premium'] ?? null,
                    'total_premium' => $responseData['data']['total_premium'] ?? null,
                    'gst' => $responseData['data']['gst'] ?? null,
                    'payment_url' => $paymentUrl ?? null,
                ];
    
                return $result;
            } else {
                return response()->json(['error' => 'Failed to retrieve payment link'], $paymentLinkResponse->status());
            }
        } else {
            return response()->json(['error' => 'Failed to retrieve proposal data'], $response->status());
        }
    }

    public function downloadPdf($enqId)
    {
        $paymentData = PaymentData::where('enqId', $enqId)->first();

        $applicationId = $paymentData->application_id;

        $downloadPdfLink = "http://healthguard/downloadpdf?application_id=$applicationId";
    
        return [
            'success' => true,
            'url' => $downloadPdfLink
        ];

    }

    public function paymentReturn($request, $name)
    {
        $payment = $request->payment;
        $policyNumber = null;

        $message = "Dear $name, your policy has been failed.";
        
        if($payment == 'success')
        {
            $message = "Dear $name, your policy has been successfully processed!";
            $policyNumber = $request->application_id;
        }

        $result = [
            'message' => $message,
            'policyNumber' => $policyNumber ?? null
        ];

        return $result;
    }
}
