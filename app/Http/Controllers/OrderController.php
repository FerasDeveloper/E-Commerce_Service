<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\DTOs\Order\CreateOrderDTO;
use App\Domains\E_Commerce\Requests\CreateOrderRequest;
use App\Domains\E_Commerce\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function __construct(
    protected OrderService $orderService
  ) {}

  public function store(CreateOrderRequest $request)
  {
    $dto = CreateOrderDTO::fromRequest($request);
    $order = $this->orderService->createFromCart($dto);

    return response()->json([
      'message' => 'Order created successfully',
      'data' => $order
    ]);
  }


  public function index(Request $request)
  {
    $userId = $request->attributes->get('auth_user')['id'];
    $projectId = $request->project_id;

    $orders = $this->orderService->listOrders($projectId, $userId);

    return response()->json([
      'message' => 'Orders fetched successfully',
      'data' => $orders
    ]);
  }


  public function show(Request $request, int $orderId)
  {
    $order = $this->orderService->getOrder($orderId, $request->project_id, $request->attributes->get('auth_user')['id']);

    return response()->json([
      'message' => 'Order fetched successfully',
      'data' => $order
    ]);
  }
}
