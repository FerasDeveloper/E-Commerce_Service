<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Models\CartItem;

class RemoveCartItemAction
{
  public function execute($itemId)
  {
    $item = CartItem::findOrFail($itemId);
    $cart = $item->cart;

    $item->delete();

    $cart->update([
      'total_price' => $cart->items->sum('total')
    ]);

    return $cart;
  }
}
