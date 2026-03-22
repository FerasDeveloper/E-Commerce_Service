<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces\Cart;

interface CartRepositoryInterface
{
  public function getActiveCart($userId);
  public function createCart($userId);
  public function updateCartTotal($cart);
}
