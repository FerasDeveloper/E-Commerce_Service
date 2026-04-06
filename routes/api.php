<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WishlistItemController;
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

  // test
  // Route::get('/{collectionSlug}/products', [ProductController::class, 'index']);
  // Route::post('/pricing/calculate', [PricingController::class, 'calculate']);
  // Route::post('/offers/{collectionSlug}/subscribe', [OfferController::class, 'subscribe']);


    // Wishlist:
    Route::prefix('wishlists')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/', [WishlistController::class, 'store']);
        Route::get('/shared/{shareToken}', [WishlistController::class, 'showSharedWishlist']);

        Route::get('/{wishlistId}', [WishlistController::class, 'show']);
        Route::put('/{wishlistId}', [WishlistController::class, 'update']);
        Route::delete('/{wishlistId}', [WishlistController::class, 'destroy']);

        Route::post('/{wishlistId}/share-link', [WishlistController::class, 'generateShareLink']);

        Route::post('/{wishlistId}/items', [WishlistItemController::class, 'store']);
        Route::delete('/{wishlistId}/items/{itemId}', [WishlistItemController::class, 'destroy']);
        Route::post('/{wishlistId}/items/reorder', [WishlistItemController::class, 'reorder']);
        Route::post('/{wishlistId}/items/{itemId}/move-to-cart', [WishlistItemController::class, 'moveToCart']);
    });
});
