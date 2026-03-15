<?php

namespace App\Domains\Offers\Actions;

use App\Domains\Offers\Repositories\Interfaces\OfferPriceRepositoryInterface;

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
