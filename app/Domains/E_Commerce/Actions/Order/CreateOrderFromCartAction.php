<?php

namespace App\Domains\E_Commerce\Actions\Order;

use App\Domains\E_Commerce\DTOs\Order\CreateOrderDTO;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Order\OrderRepositoryInterface;
use App\Domains\E_Commerce\Repositories\Interfaces\Order\OrderItemRepositoryInterface;
use Illuminate\Support\Facades\DB;

// class CreateOrderFromCartAction
// {
//   public function __construct(
//     protected OrderRepositoryInterface $orderRepo,
//     protected OrderItemRepositoryInterface $orderItemRepo,
//     protected CartRepositoryInterface $cartRepo
//   ) {}

//   public function execute(CreateOrderDTO $dto)
//   {
//     $cart = $this->cartRepo->loadItems($this->cartRepo->findById($dto->cart_id));
//     if (!$cart || $cart->items->isEmpty()) {
//       throw new \Exception('Cart is empty or not found');
//     }

//     // $order = $this->orderRepo->create([
//     //   'project_id' => $dto->project_id,
//     //   'user_id' => $dto->user_id,
//     //   'total' => $cart->items->sum('subtotal'),
//     //   'status' => 'pending',
//     // ]);
//     $order = $this->orderRepo->create([
//   'project_id' => $dto->project_id,
//   'user_id' => $dto->user_id,
//   'total_price' => $cart->items->sum('subtotal'),
//   'status' => 'pending',
// ]);

//     // foreach ($cart->items as $item) {
//     //   $this->orderItemRepo->create([
//     //     'order_id' => $order->id,
//     //     'item_id' => $item->item_id,
//     //     'quantity' => $item->quantity,
//     //     'price' => $item->price,
//     //     'subtotal' => $item->subtotal,
//     //     'status' => 'pending',
//     //   ]);
//     // }
//     foreach ($cart->items as $item) {
//   $this->orderItemRepo->create([
//     'order_id' => $order->id,
//     'product_id' => $item->item_id,
//     'product_name' => 'TODO', // لاحقاً من CMS
//     'quantity' => $item->quantity,
//     'price' => $item->price,
//     'total' => $item->subtotal,
//     'status' => 'pending',
//   ]);
// }

//     // Optionally clear cart after order creation
//     // $this->cartRepo->clear($cart->id);

//     return $this->orderRepo->loadItems($order);
//   }
// }

class CreateOrderFromCartAction
{
  public function __construct(
    protected OrderRepositoryInterface $orderRepo,
    protected OrderItemRepositoryInterface $orderItemRepo,
    protected CartRepositoryInterface $cartRepo
  ) {}

  public function execute(CreateOrderDTO $dto)
  {
    // return DB::transaction(function () use ($dto) {

    //   $cart = $this->cartRepo->loadItems(
    //     $this->cartRepo->findById($dto->cart_id)
    //   );

    //   if (!$cart || $cart->items->isEmpty()) {
    //     throw new \Exception('Cart is empty or not found');
    //   }

    //   // ✅ Security check (هون مكانها الصح)
    //   if ($cart->user_id !== $dto->user_id) {
    //     throw new \Exception('Unauthorized cart');
    //   }

    //   $order = $this->orderRepo->create([
    //     'user_id' => $dto->user_id,
    //     'total_price' => $cart->items->sum('subtotal'),
    //     'status' => 'pending',
    //   ]);

    //   foreach ($cart->items as $item) {
    //     $this->orderItemRepo->create([
    //       'order_id' => $order->id,
    //       'product_id' => $item->item_id,
    //       'product_name' => 'TODO',
    //       'quantity' => $item->quantity,
    //       'price' => $item->price,
    //       'total' => $item->subtotal,
    //       'status' => 'pending',
    //     ]);
    //   }

    //   return $this->orderRepo->loadItems($order);
    // });
    return DB::transaction(function () use ($dto) {

      $cart = $this->cartRepo->loadItems(
        $this->cartRepo->findById($dto->cart_id)
      );

      if (!$cart || $cart->items->isEmpty()) {
        throw new \Exception('Cart is empty or not found');
      }

      if ($cart->user_id !== $dto->user_id) {
        throw new \Exception('Unauthorized cart');
      }

      $order = $this->orderRepo->create([
        'project_id' => $dto->project_id,
        'user_id' => $dto->user_id,
        'total_price' => $cart->items->sum('subtotal'),
        'status' => 'pending',
        'address' => $dto->address,
      ]);

      foreach ($cart->items as $item) {
        $this->orderItemRepo->create([
          'order_id' => $order->id,
          'product_id' => $item->item_id,
          // 'product_name' => 'TODO',
          'quantity' => $item->quantity,
          'price' => $item->price,
          'total' => $item->subtotal,
          'status' => 'pending',
        ]);
      }

      // ✅ هون الإضافة الجديدة
      $this->cartRepo->delete($cart->id);

      // event(new \App\Events\SystemLogEvent(
      //   module: 'ecommerce',
      //   eventType: 'order_created',
      //   userId: $dto->user_id,
      //   entityType: 'order',
      //   entityId: $order->id,
      //   newValues: $order->toArray()
      // ));

      return $this->orderRepo->loadItems($order);
    });
  }
}
