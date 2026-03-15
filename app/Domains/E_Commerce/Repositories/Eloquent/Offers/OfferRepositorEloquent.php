<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent\Offers;

use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferRepositoryInterface;
use App\Models\Offer;
use App\Models\OfferPrice;
use Illuminate\Database\Eloquent\Collection;

class OfferRepositorEloquent implements OfferRepositoryInterface
{
  public function create(int $collectionId, array $data): Offer
  {
    $data['collection_id'] = $collectionId;
    return Offer::create($data);
  }

  public function update(int $collectionId, array $data): Offer
  {
    $offer = Offer::where('collection_id', $collectionId)->firstOrFail();
    $offer->update($data);
    return $offer;
  }

  public function findByCollectionId(int $collectionId): Offer
  {
    return Offer::where('collection_id', $collectionId)->firstOrFail();
  }

  public function reEvaluate(int $entryId): void
  {
    OfferPrice::where('entry_id', $entryId)
      ->where('is_code_price', false)
      ->update(['is_applied' => false]);

    OfferPrice::where('entry_id', $entryId)
      ->where('is_code_price', false)
      ->orderBy('final_price')
      ->limit(1)
      ->update(['is_applied' => true]);
  }

  public function getOfferDetails($collectionId): Offer
  {
    return Offer::where('collection_id', $collectionId)->first();
  }

  public function getProjectOffers($projectId): Collection
  {
    return Offer::where('project_id', $projectId)->get();
  }
}
