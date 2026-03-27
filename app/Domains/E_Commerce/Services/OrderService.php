<?php

namespace App\Domains\E_Commerce\Services;

use App\Domains\E_Commerce\Actions\Order\CreateOrderFromCartAction;
use App\Domains\E_Commerce\Actions\Order\EnrichOrderItemsAction;
use App\Domains\E_Commerce\Actions\Order\ListOrdersAction;
use App\Domains\E_Commerce\DTOs\Order\CreateOrderDTO;
use App\Domains\E_Commerce\Repositories\Interfaces\Order\OrderRepositoryInterface;

class OrderService
{
  public function __construct(
    protected OrderRepositoryInterface $orderRepo,
    protected CreateOrderFromCartAction $createFromCartAction,
    protected ListOrdersAction $listOrdersAction,
    protected EnrichOrderItemsAction $enrichOrderItemsAction
  ) {}

  public function createFromCart(CreateOrderDTO $dto)
  {
    return $this->createFromCartAction->execute($dto);
  }

  public function getOrder(int $orderId, int $projectId, int $userId)
  {
    return $this->orderRepo->findByIdForUser($orderId, $projectId, $userId);
  }

  // public function listOrders(int $projectId, int $userId)
  // {
  //   return $this->listOrdersAction->execute($projectId, $userId);
  // }

  public function listOrders(int $projectId, int $userId)
  {
    $orders = $this->listOrdersAction->execute($projectId, $userId);

    return $this->enrichOrderItemsAction->execute($orders);
  }
}
