<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Route;


Route::middleware(['resolve.project', 'auth.user'])->prefix('ecommerce')->group(function () {
  // -------------------------
  // Offers
  // -------------------------
  // CRUD
  Route::post('/offers', [OfferController::class, 'store']);
  Route::patch('/offers/{collectionSlug}', [OfferController::class, 'update']);
  Route::get('/offers/{collectionSlug}', [OfferController::class, 'show']);
  Route::delete('/offers/{collectionSlug}', [OfferController::class, 'destroy']);

  // static
  Route::get('/offers', [OfferController::class, 'index']);
  Route::post('/offers/{collectionSlug}/insert', [OfferController::class, 'addItems']);
  Route::delete('/offers/{collectionSlug}/items', [OfferController::class, 'removeItems']);
  Route::post('/offers/{collectionSlug}/deactivate', [OfferController::class, 'deactivate']);
  Route::post('/offers/{collectionSlug}/activate', [OfferController::class, 'activate']);

  // test
  Route::get('/{collectionSlug}/products', [ProductController::class, 'index']);
  Route::post('/pricing/calculate', [PricingController::class, 'calculate']);
  Route::post('/offers/{collectionSlug}/subscribe', [OfferController::class, 'subscribe']);

  // -------------------------
  // Cart
  // -------------------------
  // CRUD
  // Route::get('/cart', [])
});
