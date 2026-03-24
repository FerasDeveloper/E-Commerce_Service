<?php

namespace App\Domains\E_Commerce\Services;

use App\Domains\E_Commerce\Actions\Pricing\EnrichEntriesWithPricesAction;
use App\Domains\E_Commerce\Actions\Pricing\FetchEntriesByIdsAction;
use App\Services\CMS\CMSApiClient;

class PricingService
{
    public function __construct(
        private FetchEntriesByIdsAction $fetchEntries,
        private EnrichEntriesWithPricesAction $pricing,
        private CMSApiClient $cms
    ) {}

    // 🔥 للكارت
    public function calculate(array $entryIds, ?string $code = null): array
    {
        $entries = $this->fetchEntries->execute($entryIds);

        return $this->pricing->execute($entries, $code);
    }

    // 🔥 للـ products page
    public function fromCollection(string $collection, ?string $code = null): array
    {
        $entries = $this->cms->getCollectionBySlug($collection);
        return $this->pricing->execute($entries, $code);
    }
}