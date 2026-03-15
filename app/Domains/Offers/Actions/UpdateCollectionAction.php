<?php

namespace App\Domains\Offers\Actions;

use App\Services\CMS\CMSApiClient;

class UpdateCollectionAction
{

  public function __construct(
    protected CMSApiClient $cms,
  ) {}

  public function execute($dto)
  {
    return $this->cms->updateCollection($dto->collectionSlug, $dto->collectionData);
  }
}
