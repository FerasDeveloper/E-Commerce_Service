<?php

namespace App\Domains\E_Commerce\Services;

use App\Domains\E_Commerce\Actions\Cart\AddToCartAction;
use App\Domains\E_Commerce\Actions\Cart\ClearCartAction;
use App\Domains\E_Commerce\Actions\Cart\RemoveCartItemAction;
use App\Domains\E_Commerce\Actions\Cart\UpdateCartItemAction;

class CartService
{
    public function __construct(
        private AddToCartAction $add,
        private UpdateCartItemAction $update,
        private RemoveCartItemAction $remove,
        private ClearCartAction $clear
    ) {}

    public function add($userId, $productId, $qty)
    {
        return $this->add->execute($userId, $productId, $qty);
    }

    public function updateItem($itemId, $qty)
    {
        return $this->update->execute($itemId, $qty);
    }

    public function removeItem($itemId)
    {
        return $this->remove->execute($itemId);
    }

    public function clear($userId)
    {
        return $this->clear->execute($userId);
    }
}