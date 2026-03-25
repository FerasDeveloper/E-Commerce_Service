<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces\Cart;

use App\Models\CartItem;

interface CartItemRepositoryInterface
{
  public function findByCartAndItem(int $cartId, int $itemId);
  public function create(array $data);
  public function update(CartItem $cartItem, array $data);
  public function delete(CartItem $cartItem);
  public function deleteByCartId(int $cartId);
  public function getRealPrice(int $itemId);
}
