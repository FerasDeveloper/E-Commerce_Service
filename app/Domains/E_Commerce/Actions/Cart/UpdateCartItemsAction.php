<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\DTOs\Cart\UpdateCartItemsDTO;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartItemRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;
use RuntimeException;

class UpdateCartItemsAction
{
  public function __construct(
    protected CartRepositoryInterface $cartRepo,
    protected CartItemRepositoryInterface $cartItemRepo
  ) {}

  public function execute(UpdateCartItemsDTO $dto)
  {
    $cart = $this->cartRepo->findByProjectAndUser($dto->project_id, $dto->user_id);

    if (! $cart) {
      throw new RuntimeException('Cart not found');
    }

    foreach ($dto->items as $item) {

      $item_id  = $item['item_id'];
      $quantity = $item['quantity'];

      $cartItem = $this->cartItemRepo->findByCartAndItem($cart->id, $item_id);

      if (! $cartItem) {
        throw new RuntimeException("Cart item {$item_id} not found");
      }

      $this->cartItemRepo->update($cartItem, [
        'quantity' => $quantity,
        'subtotal' => $cartItem->price * $quantity,
      ]);
    }

    return $this->cartRepo->loadItems($cart);
  }
}
