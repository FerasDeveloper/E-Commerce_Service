<?php

namespace App\Providers;

use App\Domains\E_Commerce\Repositories\Eloquent\Offers\OfferPriceRepositoryEloquent;
use App\Domains\E_Commerce\Repositories\Eloquent\Offers\OfferRepositorEloquent;
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
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
