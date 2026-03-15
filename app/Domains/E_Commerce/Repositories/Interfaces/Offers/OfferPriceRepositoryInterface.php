<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces\Offers;

interface OfferPriceRepositoryInterface
{
  public function enterOfferItem(array $data);

  public function getLowestPriceItem($entryId);

  public function disableItemPrice($entryId);

  public function deleteOfferPricesForOffer($offerId);
}
