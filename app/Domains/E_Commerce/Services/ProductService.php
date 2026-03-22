<?php

namespace App\Domains\E_Commerce\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function getProduct($id)
    {
        return Cache::remember("product_$id", 60, function () use ($id) {
            return Http::get("cms/api/products/$id")->json();
        });
    }
}