<?php

namespace App\Domains\E_Commerce\Actions\Offers;

use App\Domains\E_Commerce\Repositories\Interfaces\Offers\OfferRepositoryInterface;
use App\Services\CMS\CMSApiClient;

class ReEvaluateEntryPricesAction
{

  public function __construct(
    protected CMSApiClient $cms,
    protected OfferRepositoryInterface $repository
  ) {}

  public function execute(array $entries)
  {
    foreach ($entries as $entry) {
      $this->repository->reEvaluate($entry['entry_id']);
    }
  }
}
