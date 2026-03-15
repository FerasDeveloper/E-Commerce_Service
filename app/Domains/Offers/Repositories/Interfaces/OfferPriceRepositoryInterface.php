<?php

namespace App\Domains\Offers\Repositories\Interfaces;

interface OfferPriceRepositoryInterface
{
  public function enterOfferItem(array $data);

  public function getLowestPriceItem($entryId);

  public function disableItemPrice($entryId);

  public function deleteOfferPricesForOffer($offerId);
}
