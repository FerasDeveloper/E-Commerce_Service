<?php

namespace App\Domains\E_Commerce\Services;


class ProductService
{
    public function __construct(
        private PricingService $pricing
    ) {}

    public function getProducts(string $collection, ?string $code = null)
    {
        return $this->pricing->fromCollection($collection, $code);
    }
}
