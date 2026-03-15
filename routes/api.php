<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;


Route::middleware('resolve.project')->prefix('ecommerce')->group(function () {
  // -------------------------
  // Offers
  // -------------------------
  Route::post('/offers', [OfferController::class, 'store']);
  Route::patch('/offers/{collectionSlug}', [OfferController::class, 'update']);
  Route::get('/offers/{collectionSlug}', [OfferController::class, 'show']);
  Route::get('/offers', [OfferController::class, 'index']);

  // -------------------------
  // Offers
  // -------------------------
  Route::delete('/offers/{offer}', [OfferController::class, 'destroy']);

  Route::get('/offers/{offer}/targets', [OfferController::class, 'targets']);
  Route::post('/offers/{offer}/targets/tree', [OfferController::class, 'replaceTargetsTree']);

  Route::get('/entries/{entryId}/applied-price', [OfferController::class, 'appliedPrice']);
});
