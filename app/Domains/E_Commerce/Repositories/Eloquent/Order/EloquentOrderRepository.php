<?php

namespace App\Domains\E_Commerce\Repositories\Eloquent\Order;

use App\Domains\E_Commerce\Repositories\Interfaces\Order\OrderRepositoryInterface;
use App\Models\Order;

class EloquentOrderRepository implements OrderRepositoryInterface
{
  public function create(array $data): Order
  {
    return Order::create($data);
  }

  public function findById(int $id): ?Order
  {
    return Order::find($id);
  }

  public function findByIdForUser(int $id, int $projectId, int $userId): ?Order
  {
    return Order::where('id', $id)
      ->where('project_id', $projectId)
      ->where('user_id', $userId)
      ->first();
  }

  public function loadItems(Order $order): Order
  {
    $order->load('items');
    return $order;
  }

  public function getUserOrders(int $projectId, int $userId)
  {
    return Order::where('project_id', $projectId)
      ->where('user_id', $userId)
      ->with('items') // 🔥 مهم
      ->latest()
      ->get();
  }
}
