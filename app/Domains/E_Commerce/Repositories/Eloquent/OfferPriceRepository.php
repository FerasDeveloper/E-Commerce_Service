<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent;

use App\Models\OfferPrice;
use App\Models\Offer;
use App\Domains\E_Commerce\Repositories\Interfaces\OfferPriceRepositoryInterface;

class OfferPriceRepository implements OfferPriceRepositoryInterface
{
    public function getAutomaticPrices(array $entryIds)
    {
        return OfferPrice::whereIn('entry_id', $entryIds)
            ->where('is_applied', true)
            ->where('is_code_price', false)
            ->orderBy('final_price') // 🔥 أرخص سعر
            ->get()
            ->groupBy('entry_id')
            ->map(fn($items) => $items->first());
    }

    public function getCodePrices(array $entryIds, string $code)
    {
        $offer = Offer::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$offer) return collect();

        return OfferPrice::whereIn('entry_id', $entryIds)
            ->where('offer_id', $offer->id)
            ->where('is_code_price', true)
            ->where('is_applied', true)
            ->get()
            ->keyBy('entry_id');
    }
}