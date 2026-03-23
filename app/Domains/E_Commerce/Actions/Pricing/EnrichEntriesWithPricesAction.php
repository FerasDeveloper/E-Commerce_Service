<?php

namespace App\Domains\E_Commerce\Actions\Pricing;

use App\Domains\E_Commerce\Repositories\Interfaces\OfferPriceRepositoryInterface;

class EnrichEntriesWithPricesAction
{
    public function __construct(
        private OfferPriceRepositoryInterface $offerRepo
    ) {}

    public function execute(array $entries, ?string $code = null): array
    {
        $entryIds = collect($entries)->pluck('id')->toArray();

        // 🔹 1. جلب الأسعار
        $autoPrices = $this->offerRepo->getAutomaticPrices($entryIds);
        $codePrices = $code ? $this->offerRepo->getCodePrices($entryIds, $code) : collect();

        // 🔹 2. الدمج
        return collect($entries)->map(function ($entry) use ($autoPrices, $codePrices) {

            $entryId = $entry['id'];

            // $originalPrice = $entry['price']; // ⚠️ حسب CMS structure
            $originalPrice = collect($entry['values'])
    ->firstWhere('key', 'price')['value'] ?? 0;

            $price = $originalPrice;
            $appliedOffer = null;

            // 🔥 الأولوية للكود
            if ($codePrices->has($entryId)) {
                $price = $codePrices[$entryId]->final_price;
                $appliedOffer = $codePrices[$entryId]->offer_id;
            }
            // 🔥 ثم automatic
            elseif ($autoPrices->has($entryId)) {
                $price = $autoPrices[$entryId]->final_price;
                $appliedOffer = $autoPrices[$entryId]->offer_id;
            }

            return [
                ...$entry,
                'original_price' => $originalPrice,
                'final_price' => $price,
                'is_offer_applied' => $appliedOffer !== null,
                'applied_offer_id' => $appliedOffer,
            ];
        })->toArray();
    }
}