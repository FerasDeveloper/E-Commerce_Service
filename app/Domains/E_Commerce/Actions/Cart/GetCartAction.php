<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;

class GetCartAction
{
  public function __construct(
    protected CartRepositoryInterface $cartRepo
  ) {}

  public function execute(int $project_id, int $user_id)
  {
    $cart = $this->cartRepo->findByProjectAndUser($project_id, $user_id);

    if (! $cart) {
      return null;
    }

    return $this->cartRepo->loadItems($cart);
  }
}
