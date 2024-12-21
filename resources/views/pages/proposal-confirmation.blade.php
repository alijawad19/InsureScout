@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/premium.css') }}">
@endsection

@section('content')
<div class="container mt-4 insurer-container">
    <div class="row">
        <!-- User Details -->
        <div class="col-md-4">
            <h3>Your Details</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $data['name'] }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $data['email'] }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $data['contact'] }}</li>
                <li class="list-group-item"><strong>Pincode:</strong> {{ $data['pincode'] }}</li>
                <li class="list-group-item"><strong>DOB:</strong> {{ $data['dob'] }}</li>
                <li class="list-group-item"><strong>Gender:</strong> {{ ucfirst($data['gender']) }}</li>
                <li class="list-group-item"><strong>Address:</strong> {{ $proposalForm['address'] }}</li>
            </ul>
        </div>

        <!-- Insurer Details -->
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3>Insurer Details</h3>
                <!-- Change Insurer Button -->
                <a href="{{ url('premium?enqId=' . $enqId) }}" class="btn btn-warning btn-sm mt-1">Change Insurer</a>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $insurerName }}</li>
                <li class="list-group-item"><strong>Tenure:</strong> {{ $tenure }} year(s)</li>
                <li class="list-group-item"><strong>Sum Insured:</strong>Rs. {{ $sumInsuredInLakh }}</li>
                <li class="list-group-item"><strong>Premium:</strong> {{ $netPremium }}</li>
                <li class="list-group-item"><strong>GST:</strong> {{ $gst }}</li>
                <li class="list-group-item"><strong>Total Premium:</strong> {{ $totalPremium }}</li>
            </ul>
        </div>

        <!-- Nominee Details -->
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3>Nominee Details</h3>
                <a href="{{ url('proposal?enqId=' . $enqId) }}" class="btn btn-info btn-sm mt-1">Edit Nominee</a>
            </div>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $proposalForm['nominee_name'] }} Doe</li>
                <li class="list-group-item"><strong>Relation:</strong> {{ ucfirst($proposalForm['nominee_relation']) }}</li>
                <li class="list-group-item"><strong>DOB:</strong> {{ $proposalForm['nominee_dob'] }}</li>
                <li class="list-group-item"><strong>Contact:</strong> {{ $proposalForm['nominee_contact'] }}</li>
            </ul>
        </div>
    </div>

    <!-- Buy Now Button -->
    <div class="text-center mt-5">
        <button id="buyNowBtn" class="btn btn-primary btn-lg">Buy Now</button>
    </div>

    <!-- Bootstrap Modal to display errors -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="errorModalLabel">Error</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="errorMessage">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
</div>
<script>
    var user_data = @json($data);
    var sumInsured = @json($sumInsured);
    var tenure = @json($tenure);
    var enqId = @json($enqId);
    var providerId = @json($providerId);
</script>
<script src="{{ asset('scripts/proposal-confirmation.js') }}"></script>
@endsection