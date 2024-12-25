@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/premium.css') }}">
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">{{ $message }}</h3>
                </div>
                <div class="card-body p-4">
                    <!-- Insurance Company Logo -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/' . $insurer_logo) }}" alt="{{ $insurer_name }}" 
                        class="img-fluid rounded" style="max-width: 180px;">
                    </div>
                    
                    @if ($policyNumber)
                        <!-- Policy Details -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Policy Number:</h5>
                            <span class="font-weight-bold text-primary">{{ $policyNumber }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Amount Paid:</h5>
                            <span class="font-weight-bold text-success">₹{{ number_format($amount, 2) }}</span>
                        </div>

                        <!-- Download Button -->
                        <div class="text-center">
                            <button id="downloadPolicyBtn" class="btn btn-success btn-lg px-4" aria-label="Download Policy PDF">
                                <i class="fas fa-download me-2"></i> Download Policy PDF
                            </button>
                        </div>
                    @else
                        <!-- Failure Message -->
                        <div class="alert alert-danger text-center my-4">
                            <i class="fas fa-exclamation-circle"></i> The policy could not be found or the application ID is invalid.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var enqId = @json($enqId);
</script>
<script src="{{ asset('scripts/payment-confirmation.js') }}" defer></script>
@endsection
