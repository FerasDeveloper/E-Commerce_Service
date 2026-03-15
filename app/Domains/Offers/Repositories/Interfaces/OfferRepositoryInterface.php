<?php

namespace App\Domains\Offers\Repositories\Interfaces;

use App\Models\Offer;

interface OfferRepositoryInterface
{
  public function create(int $collectionId, array $data): Offer;

  public function update(int $collectionId, array $data): Offer;

  public function findByCollectionId(int $collectionId): Offer;

  public function reEvaluate(int $entryId): void;
}
