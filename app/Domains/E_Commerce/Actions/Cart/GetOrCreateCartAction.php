<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;

class GetOrCreateCartAction
{
  public function __construct(
    private CartRepositoryInterface $repository
  ) {}

  public function execute($dto)
  {
    $cart = $this->repository->getCart($dto->project_id, $dto->user_id);

    if (!$cart) {
      $cart = $this->repository->createCart($dto);
    }

    return $cart;
  }
}
