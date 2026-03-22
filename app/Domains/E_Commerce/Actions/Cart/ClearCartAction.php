<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Models\Cart;

class ClearCartAction
{
    public function execute($userId)
    {
        $cart = Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            return null;
        }

        $cart->items()->delete();

        $cart->update([
            'total_price' => 0
        ]);

        return $cart;
    }
}