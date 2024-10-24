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

            <!-- Accordion for Sum Insured and Tenure -->
            <div class="accordion mt-4" id="optionsAccordion">
                <!-- Sum Insured -->
                <div class="card">
                    <div class="card-header" id="headingSumInsured">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            Sum Insured
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSumInsured" aria-expanded="true" aria-controls="collapseSumInsured">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseSumInsured" class="collapse show" aria-labelledby="headingSumInsured" data-parent="#optionsAccordion">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="500000" id="sumInsured1" checked>
                                <label class="form-check-label" for="sumInsured1">5 Lakh</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1000000" id="sumInsured2">
                                <label class="form-check-label" for="sumInsured2">10 Lakh</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2500000" id="sumInsured3">
                                <label class="form-check-label" for="sumInsured3">25 Lakh</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tenure -->
                <div class="card">
                    <div class="card-header" id="headingTenure">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            Tenure
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTenure" aria-expanded="true" aria-controls="collapseTenure">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTenure" class="collapse show" aria-labelledby="headingTenure" data-parent="#optionsAccordion">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="tenure1" checked>
                                <label class="form-check-label" for="tenure1">1 year</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2" id="tenure2">
                                <label class="form-check-label" for="tenure2">2 years</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="tenure3">
                                <label class="form-check-label" for="tenure3">3 years</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9 insurer-table">
            <h2 class="text-center">Available Insurance Plans</h2>

            <div class="row mb-2">
                <div class="col-md-4 text-center">
                    <h5>Insurer</h5>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Sum Insured</h5>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Premium (excl. GST)</h5>
                </div>
            </div>

            <!-- Insurer Items - Rendered dynamically -->
            <div id="insurer-items" class="col-md-12 mb-4"></div>
        </div>
    </div>
</div>
<script>
    var activeProviders = @json($activeProviders);
    var user_data = @json($data);
</script>
<script src="{{ asset('scripts/premium.js') }}"></script>
@endsection
