<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ReturnRequestController;
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
  // Route::get('/{collectionSlug}/products', [ProductController::class, 'index']);
  Route::get('/products/{dataTypeSlug}', [ProductController::class, 'index']);
  Route::post('/pricing/calculate', [PricingController::class, 'calculate']);
  Route::post('/offers/{collectionSlug}/subscribe', [OfferController::class, 'subscribe']);

  // -------------------------
  // Cart
  // -------------------------
  // CRUD
  Route::post('/cart', [CartController::class, 'store']);
  Route::get('/cart', [CartController::class, 'show']);
  Route::put('/cart', [CartController::class, 'update']);
  Route::delete('/cart/items', [CartController::class, 'remove']);
  Route::delete('/cart', [CartController::class, 'clear']);

  // Payment Gateways
  Route::post('/payments', [PaymentController::class, 'charge']);
  Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund']);

  // order

  Route::post('/orders/from-cart', [OrderController::class, 'store']);
  Route::get('/orders', [OrderController::class, 'index']);
  Route::get('/allorders', [OrderController::class, 'adminIndex']);
  Route::get('/orders/{orderId}', [OrderController::class, 'show']);
  Route::patch('/orders/{orderId}/status', [OrderController::class, 'updateStatus']);
  Route::post('/return-requests', [ReturnRequestController::class, 'store']);
  Route::patch('/admin/return-requests/{id}', [ReturnRequestController::class, 'update']);
  Route::get('/admin/return-requests', [ReturnRequestController::class, 'index']);
  
  // test
  // Route::get('/{collectionSlug}/products', [ProductController::class, 'index']);
  // Route::post('/pricing/calculate', [PricingController::class, 'calculate']);
  // Route::post('/offers/{collectionSlug}/subscribe', [OfferController::class, 'subscribe']);
});
