<?php

namespace App\Domains\E_Commerce\Services;

use App\Domains\E_Commerce\Actions\Pricing\EnrichEntriesWithPricesAction;
use App\Services\CMS\CMSApiClient as CMSCMSApiClient;

class ProductService
{
  public function __construct(
    private CMSCMSApiClient $cms,
    private EnrichEntriesWithPricesAction $pricing
  ) {}

  public function getProducts(string $collection, ?string $code = null)
  {
    $entries = $this->cms->getEntries($collection);
    return $this->pricing->execute($entries, $code);
  }
}
