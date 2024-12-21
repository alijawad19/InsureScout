<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('index');
Route::post('/', [ServiceController::class, 'index']);
Route::get('/premium', [ServiceController::class, 'premium'])->name('premium');
Route::post('/premium/{providerId}', [ServiceController::class, 'getPremiumData']);
Route::get('/proposal/{enqId}', [ServiceController::class, 'proposal']);
Route::get('/proposal', [ServiceController::class, 'showProposal'])->name('proposal.show');
Route::post('/proposal', [ServiceController::class, 'storeProposalData']);
Route::get('/proposal-confirmation', [ServiceController::class, 'confirmProposal'])->name('proposal.confirm');
Route::post('/generate-proposal/{providerId}', [ServiceController::class, 'generateProposal']);
Route::get('/payment-confirmation', [ServiceController::class, 'paymentReturn']);
Route::get('/confirmation', [ServiceController::class, 'showProposal'])->name('payment.show');
Route::post('/download-policy/{enqId}', [ServiceController::class, 'downloadPdf']);
