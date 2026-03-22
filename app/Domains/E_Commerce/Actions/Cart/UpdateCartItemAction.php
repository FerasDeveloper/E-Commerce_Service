<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Models\CartItem;

class UpdateCartItemAction
{
  public function execute($itemId, $quantity)
  {
    $item = CartItem::findOrFail($itemId);

    $item->quantity = $quantity;
    $item->total = $item->price * $quantity;
    $item->save();

    $cart = $item->cart;
    $cart->update([
      'total_price' => $cart->items->sum('total')
    ]);

    return $cart;
  }
}
