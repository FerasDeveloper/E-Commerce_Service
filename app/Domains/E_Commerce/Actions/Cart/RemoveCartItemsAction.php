<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\DTOs\Cart\RemoveCartItemsDTO;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartItemRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;
use RuntimeException;

class RemoveCartItemsAction
{
  public function __construct(
    protected CartRepositoryInterface $cartRepo,
    protected CartItemRepositoryInterface $cartItemRepo
  ) {}

  public function execute(RemoveCartItemsDTO $dto)
  {
    $cart = $this->cartRepo->findByProjectAndUser($dto->project_id, $dto->user_id);

    if (! $cart) {
      throw new RuntimeException('Cart not found');
    }

    foreach ($dto->items as $item) {
      $item_id = $item['item_id'];
      $cartItem = $this->cartItemRepo->findByCartAndItem($cart->id, $item_id);

      if ($cartItem) {
        $this->cartItemRepo->delete($cartItem);
      }
    }
    return $this->cartRepo->loadItems($cart);
  }
}
