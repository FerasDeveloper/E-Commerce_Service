<?php

namespace App\Domains\E_Commerce\Actions\Offers;

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
