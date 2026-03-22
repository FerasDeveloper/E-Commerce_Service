<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces\Offers;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface OfferRepositoryInterface
{
  public function create(int $collectionId, array $data): Offer;

  public function update(int $collectionId, array $data): Offer;

  public function findByCollectionId(int $collectionId): Offer;

  public function reEvaluate(int $entryId): void;

  public function getOfferDetails($collectionId): Offer;

  public function getProjectOffers($projectId): Collection;

  public function deleteOfferByCollectionId($collectionId): void;

  public function deactivateOffer($collectionId): void;

  public function activateOffer($collectionId): void;

  public function activateDueOffers(Carbon $now): int;

  public function deactivateExpiredOffers(Carbon $now): int;
}
