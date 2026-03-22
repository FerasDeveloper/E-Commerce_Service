<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;

class GetOrCreateCartAction
{
  public function __construct(
    private CartRepositoryInterface $cartRepo
  ) {}

  public function execute($userId)
  {
    $cart = $this->cartRepo->getActiveCart($userId);

    if (!$cart) {
      $cart = $this->cartRepo->createCart($userId);
    }

    return $cart;
  }
}
