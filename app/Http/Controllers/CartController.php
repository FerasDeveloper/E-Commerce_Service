<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function __construct(private CartService $service) {}

  public function add(Request $request)
  {
    return $this->service->add(
      auth()->id(),
      $request->product_id,
      $request->quantity
    );
  }

  public function update($id, Request $request)
  {
    return $this->service->updateItem($id, $request->quantity);
  }

  public function remove($id)
  {
    return $this->service->removeItem($id);
  }

  public function clear()
  {
    return $this->service->clear(auth()->id());
  }
}
