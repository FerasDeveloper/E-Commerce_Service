<?php

namespace App\Providers;

use App\Domains\E_Commerce\Repositories\Eloquent\Cart\EloquentCartItemRepository;
use App\Domains\E_Commerce\Repositories\Eloquent\Cart\EloquentCartRepository;
use App\Domains\E_Commerce\Repositories\Eloquent\Offers\OfferPriceRepositoryEloquent;
use App\Domains\E_Commerce\Repositories\Eloquent\Offers\OfferRepositorEloquent;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartItemRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferPriceRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(OfferRepositoryInterface::class, OfferRepositorEloquent::class);
    $this->app->bind(OfferPriceRepositoryInterface::class, OfferPriceRepositoryEloquent::class);
    $this->app->bind(CartRepositoryInterface::class, EloquentCartRepository::class);
    $this->app->bind(CartItemRepositoryInterface::class, EloquentCartItemRepository::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
