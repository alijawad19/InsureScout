@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">

<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <h3>InsureScout</h3>
                    <p>Fill in the data below.</p>
                    <form action="{{ url('/') }}" method="POST" class="requires-validation" novalidate>
                        @csrf
                        <div class="col-md-12">
                           <input class="form-control" type="text" name="name" placeholder="Full Name" required>
                           <div class="valid-feedback">Name field is valid!</div>
                           <div class="invalid-feedback">Name field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row align-items-center">
                                <label for="dob" class="col-sm-3 col-form-label">Date of Birth:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="date" name="dob" id="dob" required>
                                    <div class="valid-feedback">Date of Birth is valid!</div>
                                    <div class="invalid-feedback">Date of Birth field cannot be blank!</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="gender">Gender: </label>

                            <input type="radio" class="btn-check" name="gender" id="male" value="male" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="male">Male</label>

                            <input type="radio" class="btn-check" name="gender" id="female" value="female" autocomplete="off" required>
                            <label class="btn btn-sm btn-outline-secondary" for="female">Female</label>
                            <div class="valid-feedback mv-up">You selected a gender!</div>
                            <div class="invalid-feedback mv-up">Please select a gender!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                            <div class="valid-feedback">Email field is valid!</div>
                            <div class="invalid-feedback">Email field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                           <input class="form-control" type="tel" name="contact" placeholder="Contact Number" pattern="[0-9]{10}" required>
                           <div class="valid-feedback">Contact field is valid!</div>
                           <div class="invalid-feedback">Please provide a valid 10-digit contact number!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="pincode" placeholder="Pincode" pattern="[0-9]{6}" required>
                            <div class="valid-feedback">Pincode field is valid!</div>
                            <div class="invalid-feedback">Please provide a valid 6-digit pincode!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('scripts/index.js') }}"></script>

@endsection
