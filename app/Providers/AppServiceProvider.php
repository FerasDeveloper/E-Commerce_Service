<?php

namespace App\Providers;

use App\Domains\Offers\Repositories\Eloquent\OfferPriceRepositoryEloquent;
use App\Domains\Offers\Repositories\Eloquent\OfferRepositorEloquent;
use App\Domains\Offers\Repositories\Interfaces\OfferPriceRepositoryInterface;
use App\Domains\Offers\Repositories\Interfaces\OfferRepositoryInterface;
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
