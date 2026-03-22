<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent\Cart;

use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;
use App\Models\Cart;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function getActiveCart($userId)
    {
        return Cart::with('items')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    public function createCart($userId)
    {
        return Cart::create([
            'user_id' => $userId,
            'status' => 'active',
            'total_price' => 0
        ]);
    }

    public function updateCartTotal($cart)
    {
        $total = $cart->items->sum('total');

        $cart->update([
            'total_price' => $total
        ]);

        return $cart;
    }
}