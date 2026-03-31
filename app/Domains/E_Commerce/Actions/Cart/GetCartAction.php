<?php

namespace App\Domains\E_Commerce\Actions\Cart;

use App\Domains\E_Commerce\Actions\Pricing\EnrichEntriesWithPricesAction;
use App\Domains\E_Commerce\Actions\Pricing\FetchEntriesByIdsAction;
use App\Domains\E_Commerce\Repositories\Interfaces\Cart\CartRepositoryInterface;

class GetCartAction
{
  public function __construct(
    protected CartRepositoryInterface       $cartRepo,
    protected FetchEntriesByIdsAction       $fetchEntries,
    protected EnrichEntriesWithPricesAction $enrichPrices,
  ) {}

  public function execute(int $project_id, int $user_id): array
  {
    $cart = $this->cartRepo->getOrCreate($project_id, $user_id);
    $cart = $this->cartRepo->loadItems($cart);

    if ($cart->items->isEmpty()) {
      return [
        'cart_id'     => $cart->id,
        'items'       => [],
        'total'       => 0,
        'total_items' => 0,
      ];
    }

    // 1 — جلب item_ids من السلة
    $itemIds = $cart->items->pluck('item_id')->toArray();

    // 2 — جلب بيانات العناصر من CMS
    $entries = $this->fetchEntries->execute($itemIds);

    // 3 — إضافة أفضل سعر لكل عنصر (عروض تلقائية + عروض المستخدم)
    $enrichedEntries = $this->enrichPrices->execute($entries);

    // 4 — تحويل إلى map لسهولة الوصول
    $entriesMap = collect($enrichedEntries)->keyBy('id');

    // 5 — بناء الـ response مع السعر الحالي
    $items = $cart->items->map(function ($cartItem) use ($entriesMap) {
      $entry    = $entriesMap[$cartItem->item_id] ?? null;
      $price    = $entry['final_price']    ?? 0;
      $subtotal = $price * $cartItem->quantity;

      return [
        'cart_item_id'     => $cartItem->id,
        'item_id'          => $cartItem->item_id,
        'quantity'         => $cartItem->quantity,
        'original_price'   => $entry['original_price']   ?? 0,
        'final_price'      => $price,
        'subtotal'         => $subtotal,
        'is_offer_applied' => $entry['is_offer_applied'] ?? false,
        'applied_offer_id' => $entry['applied_offer_id'] ?? null,
        'entry'            => $entry,
      ];
    });

    return [
      'cart_id'     => $cart->id,
      'items'       => $items->values(),
      'total'       => $items->sum('subtotal'),
      'total_items' => $items->sum('quantity'),
    ];
  }
}
