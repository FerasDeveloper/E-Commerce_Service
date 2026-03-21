<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent\Offers;

use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferPriceRepositoryInterface;
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

  public function deleteOfferPrice($offerPriceId): void
  {
    OfferPrice::where('id', $offerPriceId)->delete();
  }

  public function getEntryPrice(int $entryId, int $offerId)
  {
    return OfferPrice::where('entry_id', $entryId)->where('applied_offer_id', $offerId)->first();
  }

  public function deleteOfferPriceForEntryAndProject(int $entryId, int $offerId): void
  {
    $item = OfferPrice::where('entry_id', $entryId)->where('applied_offer_id', $offerId)->first();
    if ($item)
      $item->delete();
  }
}
