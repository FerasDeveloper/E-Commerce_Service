<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent\Order;

use App\Domains\E_Commerce\Repositories\Interfaces\Order\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class EloquentOrderItemRepository implements OrderItemRepositoryInterface
{
  public function create(array $data)
  {
    return OrderItem::create($data);
  }

  public function update($item, array $data)
  {
    $item->update($data);
    return $item;
  }

  public function findByOrderAndItem(int $orderId, int $itemId)
  {
    return OrderItem::where('order_id', $orderId)
      ->where('item_id', $itemId)
      ->first();
  }
}
