<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('index'); ;
Route::post('/', [ServiceController::class, 'index']);
Route::get('/premium', [ServiceController::class, 'premium'])->name('premium');
Route::post('/premium/{providerId}', [ServiceController::class, 'getPremiumData']);
Route::get('/proposal/{enqId}', [ServiceController::class, 'proposal']);
Route::get('/proposal', [ServiceController::class, 'showProposal'])->name('proposal.show');

Route::post('/calculate-premium', [ServiceController::class, 'calculatePremium']);
Route::post('/generate-proposal', [ServiceController::class, 'generateProposal']);
