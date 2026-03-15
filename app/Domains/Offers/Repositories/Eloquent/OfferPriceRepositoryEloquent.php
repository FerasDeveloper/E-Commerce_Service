<?php

namespace App\Domains\Offers\Repositories\Eloquent;

use App\Domains\Offers\Repositories\Interfaces\OfferPriceRepositoryInterface;
use App\Models\OfferPrice;

class OfferPriceRepositoryEloquent implements OfferPriceRepositoryInterface
{
  public function enterOfferItem(array $data)
  {
    OfferPrice::create($data);
  }

  public function getLowestPriceItem($entryId)
  {
    return OfferPrice::where('entry_id', $entryId)->where('is_applied', true)->where('is_code_price', false)->first();
  }

  public function disableItemPrice($entryId)
  {
    OfferPrice::where('entry_id', $entryId)->where('is_applied', true)->where('is_code_price', false)->update([
      'is_applied' => false
    ]);
  }

  public function deleteOfferPricesForOffer($offerId)
  {
    OfferPrice::where('applied_offer_id', $offerId)->delete();
  }
}
