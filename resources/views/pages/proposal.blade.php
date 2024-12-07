@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/premium.css') }}">
@endsection

@section('content')
<div class="container mt-4 insurer-container">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <h2>Your Details</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $data['name'] }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $data['email'] }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $data['contact'] }}</li>
                <li class="list-group-item"><strong>Pincode:</strong> {{ $data['pincode'] }}</li>
                <li class="list-group-item"><strong>DOB:</strong> {{ $data['dob'] }}</li>
                <li class="list-group-item"><strong>Gender:</strong> {{ ucfirst($data['gender']) }}</li>
            </ul>

            <h2>Insurer Details</h2>
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $insurerName }}</li>
                <li class="list-group-item"><strong>Tenure:</strong> {{ $tenure }} year(s)</li>
                <li class="list-group-item"><strong>Sum Insured:</strong>Rs. {{ $sumInsuredInLakh }}</li>
                <li class="list-group-item"><strong>Premium:</strong> {{ $netPremium }}</li>
                <li class="list-group-item"><strong>GST:</strong> {{ $gst }}</li>
                <li class="list-group-item"><strong>Total Premium:</strong> {{ $totalPremium }}</li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 insurer-table">
            <h2 class="text-center">Proposal Form</h2>
            <form action="{{ url('/proposal') }}?enqId={{ $enqId }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4>Your Details</h4>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $proposalForm['address'] ?? '') }}" placeholder="e.g., 123 Street Name" required>
                        </div>

                        <h4 class="mt-4">Nominee Details</h4>
                        <div class="form-group">
                            <label for="nominee_name">Nominee Name</label>
                            <input type="text" class="form-control" id="nominee_name" name="nominee_name" value="{{ old('nominee_name', $proposalForm['nominee_name'] ?? '') }}" placeholder="e.g., John Doe"  required>
                        </div>
                        <div class="form-group">
                            <label for="nominee_relation">Relation</label>
                            <select class="form-control" id="nominee_relation" name="nominee_relation" required>
                                <option value="">Select Relation</option>
                                <?php 
                                $relations = ['father' => 'Father', 'mother' => 'Mother', 'spouse' => 'Spouse', 'son' => 'Son', 'daughter' => 'Daughter', 'brother' => 'Brother', 'sister' => 'Sister'];
                                $selectedRelation = old('nominee_relation', $proposalForm['nominee_relation'] ?? '' ?? '');
                                ?>
                                <?php foreach ($relations as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $selectedRelation === $value ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nominee_dob">Nominee Date of Birth</label>
                            <input type="date" class="form-control" id="nominee_dob" name="nominee_dob" value="{{ old('nominee_dob', $proposalForm['nominee_dob'] ?? '') }}"  required>
                        </div>
                        <div class="form-group">
                            <label for="nominee_contact">Nominee Contact Number</label>
                            <input type="tel" class="form-control" id="nominee_contact" name="nominee_contact" value="{{ old('nominee_contact', $proposalForm['nominee_contact'] ?? '') }}" placeholder="e.g., 9876543210" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4">Submit Proposal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var user_data = @json($data);
</script>
@endsection