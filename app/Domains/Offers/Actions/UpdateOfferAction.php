<?php

namespace App\Domains\Offers\Actions;

use App\Domains\Offers\Repositories\Interfaces\OfferRepositoryInterface;

class UpdateOfferAction
{

  public function __construct(
    protected OfferRepositoryInterface $repository
  ) {}

  public function execute(string $collectionId, $dto)
  {
    return $this->repository->update($collectionId, $dto->offerData);
  }
}
