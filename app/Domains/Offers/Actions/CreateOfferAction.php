<?php

namespace App\Domains\Offers\Actions;

use App\Domains\Offers\Repositories\Interfaces\OfferRepositoryInterface;
use App\Services\CMS\CMSApiClient;

class CreateOfferAction
{

  public function __construct(
    protected CMSApiClient $cms,
    protected OfferRepositoryInterface $repository
  ) {}

  public function execute($dto)
  {
    $response = $this->cms->createCollection($dto->CollectionToArray());

    if (!isset($response['data'])) {
      throw new \Exception("Failed to create collection in CMS");
    }

    $collection = $response['data'];

    $offer = $this->repository->create($collection['id'], $dto->OfferToArray());

    return [
      'collection' => $collection,
      'offer' => $offer
    ];
  }
}
