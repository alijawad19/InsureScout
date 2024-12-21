<?php

namespace App\Http\Controllers;

use App\Models\PaymentData;
use App\Models\PremiumData;
use App\Models\ProposalData;
use App\Models\ProposalForm;
use App\Models\Provider;
use DateTime;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before:-18 years',
            'gender' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|string|max:10',
            'pincode' => 'required|string|size:6',
        ]);

        $premiumData = PremiumData::create($data);
    
        $enqId = $premiumData->id;
    
        return redirect()->route('premium', ['enqId' => $enqId]);
    
    }

    public function premium(Request $request)
    {
        $enqId = $request->query('enqId');
    
        if (!$enqId || !($data = PremiumData::find($enqId))) {
            return redirect()->route('index');
        }
    
        $activeProviders = Provider::where('is_active', '1')->get();
    
        return view('pages.premium', [
            'data' => $data,
            'activeProviders' => $activeProviders,
        ]);
    }

    public function getPremiumData($providerId)
    {
        $sumInsured = request()->input('sum_insured');
        $tenure = request()->input('tenure');
        $userData = request()->input('user_data');
    
        $provider = Provider::where('provider_id', $providerId)->first();
    
        if (!$provider) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        $dob = new DateTime($userData['dob']);
        $age = (new DateTime())->diff($dob)->y;

        $controllerClassName = sprintf('App\\Http\\Controllers\\%sController', $provider->ic_name);

        if (!class_exists($controllerClassName)) {
            return response()->json(['error' => 'PRemium method not found for this provider'], 404);
        }

        $premiumData = (new $controllerClassName())->calculatePremium($sumInsured, $tenure, $age, $userData);

        $sumInsuredInLakh = $this->convertToLakh($sumInsured);

        $response = [
            $providerId => [
                "tenure" => $tenure,
                "sum_insured" => $sumInsuredInLakh,
                "insurer_name" => $provider->ic_name,
                "insurer_logo" => $provider->ic_name . ".png",
                "net_premium" => $premiumData['net_premium'],
                "total_premium" => $premiumData['total_premium'],
                "gst" => $premiumData['gst']
            ]
        ];
    
        return response()->json($response[$providerId] ?? null);
    }

    public function proposal($enqId, Request $request)
    {
        // Get the query parameters
        $providerId = $request->query('provider_id');
        $tenure = $request->query('tenure');
        $sumInsured = $request->query('sum_insured');
        $netPremium = $request->query('net_premium');
        $gst = $request->query('gst');
        $totalPremium = $request->query('total_premium');
    
        // Check if enqId is valid and PremiumData exists
        if (!$enqId || !($data = PremiumData::find($enqId))) {
            return redirect()->route('index');
        }

        ProposalData::updateOrCreate(
            ['enqId' => $enqId],
            [
                'provider_id' => $providerId,
                'tenure' => $tenure,
                'sum_insured' => $sumInsured,
                'net_premium' => $netPremium,
                'gst' => $gst,
                'total_premium' => $totalPremium
            ]
        );
    
        return redirect()->route('proposal.show', ['enqId' => $enqId]);    
    }

    public function showProposal(Request $request)
    {
        $enqId = $request->query('enqId');
    
        $proposalData = ProposalData::where('enqId', $enqId)->first();
    
        if (!$proposalData) {
            return redirect()->route('index')->with('error', 'No proposal data found.');
        }

        $provider = Provider::where('provider_id', $proposalData->provider_id)->first();

        $data = PremiumData::find($enqId);

        $sumInsuredInLakh = $this->convertToLakh($proposalData->sum_insured);

        if (!$enqId || !($data = PremiumData::find($enqId))) {
            return redirect()->route('index');
        }

        $proposalForm = ProposalForm::where('enqId', $enqId)->first();
    
        // Pass this data to the view
        return view('pages.proposal', [
            'enqId' => $proposalData->enqId,
            'providerId' => $proposalData->provider_id,
            'insurerName' => $provider->ic_name,
            'tenure' => $proposalData->tenure,
            'sumInsured' => $proposalData->sum_insured,
            'sumInsuredInLakh' => $sumInsuredInLakh,
            'netPremium' => $proposalData->net_premium,
            'gst' => $proposalData->gst,
            'totalPremium' => $proposalData->total_premium,
            'data' => $data,
            'proposalForm' => $proposalForm
        ]);
    }

    public function storeProposalData(Request $request)
    {
        $enqId = $request->query('enqId');

        $validated = $request->validate([
            'enqId' => 'required|integer',
            'address' => 'required|string|max:255',
            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:50',
            'nominee_dob' => 'required|date',
            'nominee_contact' => 'required|string|max:10',
        ]);

        ProposalForm::updateOrCreate(
            ['enqId' => $validated['enqId']],
            [
                'address' => $validated['address'],
                'nominee_name' => $validated['nominee_name'],
                'nominee_relation' => $validated['nominee_relation'],
                'nominee_dob' => $validated['nominee_dob'],
                'nominee_contact' => $validated['nominee_contact'],
            ]
        );

        return redirect()->route('proposal.confirm', ['enqId' => $enqId]);    
    }

    public function confirmProposal(Request $request)
    {
        $enqId = $request->query('enqId');
    
        $proposalData = ProposalData::where('enqId', $enqId)->first();
    
        if (!$proposalData) {
            return redirect()->route('index')->with('error', 'No proposal data found.');
        }

        $provider = Provider::where('provider_id', $proposalData->provider_id)->first();

        $data = PremiumData::find($enqId);

        $sumInsuredInLakh = $this->convertToLakh($proposalData->sum_insured);

        if (!$enqId || !($data = PremiumData::find($enqId))) {
            return redirect()->route('index');
        }

        $proposalForm = ProposalForm::where('enqId', $enqId)->first();
    
        // Pass this data to the view
        return view('pages.proposal-confirmation', [
            'enqId' => $proposalData->enqId,
            'providerId' => $proposalData->provider_id,
            'insurerName' => $provider->ic_name,
            'tenure' => $proposalData->tenure,
            'sumInsured' => $proposalData->sum_insured,
            'sumInsuredInLakh' => $sumInsuredInLakh,
            'netPremium' => $proposalData->net_premium,
            'gst' => $proposalData->gst,
            'totalPremium' => $proposalData->total_premium,
            'data' => $data,
            'proposalForm' => $proposalForm
        ]);
    }

    public function generateProposal($providerId)
    {
        $sumInsured = request()->input('sum_insured');
        $tenure = request()->input('tenure');
        $enqId = request()->input('enqId');
    
        $provider = Provider::where('provider_id', $providerId)->first();
    
        if (!$provider) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        $controllerClassName = sprintf('App\\Http\\Controllers\\%sController', $provider->ic_name);

        if (!class_exists($controllerClassName)) {
            return response()->json(['error' => 'Proposal method not found for this provider'], 404);
        }

        $proposalResponse = (new $controllerClassName())->createProposal($enqId);
    
        return response()->json($proposalResponse ?? null);
    }

    public function paymentReturn(Request $request)
    {
        $enqId = $request->query('enqId');
        $premiumData = PremiumData::find($enqId);

        if (!$premiumData) {
            return redirect()->route('index');
        }

        $proposalData = ProposalData::where('enqId', $enqId)->first();
        
        if (!$proposalData) {
            return redirect()->route('index');
        }

        $providerId = $proposalData->provider_id;
        $message = "Dear $premiumData->name, your policy has been failed.";
        $policyNumber = null;

        switch ($providerId) {
            case 1:
                $policyNumber = $request->query('application_id');
                break;
        }

        if (!empty($policyNumber)) {
            $message = "Dear $premiumData->name, your policy has been successfully processed!";
            PaymentData::updateOrCreate(
                ['enqId' => $enqId],
                [
                    'application_id' => $policyNumber,
                    'payment_status' => '1',
                ]
            );
        }

        $amount = $proposalData->total_premium;

        $provider = Provider::where('provider_id', $providerId)->first();

        return view('pages.payment-confirmation', [
            'enqId' => $proposalData->enqId,
            'message' => $message,
            'policyNumber' => $policyNumber,
            'amount' => $amount,
            "insurer_name" => $provider->ic_name,
            "insurer_logo" => $provider->ic_name . ".png"
        ]);
    }

    public function downloadPdf($enqId)
    {
        $proposalData = ProposalData::where('enqId', $enqId)->first();
        
        if (!$proposalData) {
            return redirect()->route('index');
        }

        $providerId = $proposalData->provider_id;

        $provider = Provider::where('provider_id', $providerId)->first();
    
        if (!$provider) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        $controllerClassName = sprintf('App\\Http\\Controllers\\%sController', $provider->ic_name);

        if (!class_exists($controllerClassName)) {
            return response()->json(['error' => 'Download PDF method not found for this provider'], 404);
        }

        $pdfDownloadResponse = (new $controllerClassName())->downloadPdf($enqId);
    
        return response()->json($pdfDownloadResponse ?? null);
    }

    private function logToFile($message, $uniqueId, $logType = 'request')
    {
        $logFile = storage_path('logs/' . $uniqueId . '_' . $logType . '.txt');
        $logMessage = json_encode($message);
        file_put_contents($logFile, $logMessage);
    }

    private function convertToLakh($amount)
    {
        return $amount / 100000 . ' lakh';
    }

}
