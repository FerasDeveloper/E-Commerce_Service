<?php

namespace App\Domains\E_Commerce\Repositories\Interfaces\Order;

interface OrderItemRepositoryInterface
{
  public function create(array $data);
  public function update($item, array $data);
  public function findByOrderAndItem(int $orderId, int $itemId);
}
