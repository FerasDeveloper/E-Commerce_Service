<?php

namespace App\Domains\E_Commerce\DTOs\Offers;

use App\Domains\E_Commerce\Requests\DeactivateOfferRequest;

class DeactiveOfferDTO
{
  public function __construct(
    public string $collectionSlug,
    public bool $is_active
  ) {}

  public static function fromRequest(string $collectionSlug, DeactivateOfferRequest $request): self
  {
    return new self(
      collectionSlug: $collectionSlug,
      is_active: $request->is_active
    );
  }
}
