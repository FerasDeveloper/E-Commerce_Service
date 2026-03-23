<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces;

interface OfferPriceRepositoryInterface
{
    public function getAutomaticPrices(array $entryIds);
    public function getCodePrices(array $entryIds, string $code);
}