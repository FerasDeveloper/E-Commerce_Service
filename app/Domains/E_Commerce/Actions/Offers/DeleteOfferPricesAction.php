<?php

namespace App\Domains\E_Commerce\Actions\Offers;

use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferPriceRepositoryInterface;

class DeleteOfferPricesAction
{
  public function __construct(
    protected OfferPriceRepositoryInterface $repository
  ) {}

  public function execute(int $offerId)
  {
    $this->repository->deleteOfferPricesForOffer($offerId);
  }
}
