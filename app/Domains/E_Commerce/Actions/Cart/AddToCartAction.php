<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\Services\ProductService;
use Exception;

class AddToCartAction
{
  public function __construct(
    private GetOrCreateCartAction $getCart,
    private ProductService $productService
  ) {}

  public function execute($userId, $productId, $quantity)
  {
    $cart = $this->getCart->execute($userId);

    // 🔹 1. جيب المنتج من CMS
    $product = $this->productService->getProduct($productId);

    if (!$product) {
      throw new Exception("Product not found");
    }

    $price = $product['price'];

    // 🔹 2. تحقق إذا المنتج موجود مسبقاً
    $item = $cart->items()
      ->where('product_id', $productId)
      ->first();

    if ($item) {
      $item->quantity += $quantity;
      $item->total = $item->quantity * $price;
      $item->save();
    } else {
      $cart->items()->create([
        'product_id' => $productId,
        'quantity' => $quantity,
        'price' => $price,
        'total' => $price * $quantity
      ]);
    }

    // 🔹 3. تحديث total
    $cart->load('items');
    $cart->update([
      'total_price' => $cart->items->sum('total')
    ]);

    return $cart;
  }
}
