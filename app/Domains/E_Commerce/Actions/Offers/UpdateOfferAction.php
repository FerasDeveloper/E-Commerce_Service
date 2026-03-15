<?php

namespace App\Domains\E_Commerce\Actions\Offers;

use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferRepositoryInterface;

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
