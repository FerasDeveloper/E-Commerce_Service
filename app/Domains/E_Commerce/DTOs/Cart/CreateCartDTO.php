<?php

namespace App\Domains\E_Commerce\DTOs\Cart;

use App\Domains\E_Commerce\Requests\InsertOfferItemsRequest;

class OfferItemsDTO
{
  public function __construct(
    public string $collectionSlug,
    public array $items
  ) {}

  public static function fromRequest(string $collectionSlug, InsertOfferItemsRequest $request): self
  {
    return new self(
      collectionSlug: $collectionSlug,
      items: $request->validated()['items']
    );
  }
}
