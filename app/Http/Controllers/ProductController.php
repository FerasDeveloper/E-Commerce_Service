<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function __construct(private ProductService $service) {}

  public function index(Request $request)
  {
    return $this->service->getProducts(
      'products',
      $request->code
    );
  }
}


// POST /pricing/calculate
// {
//   "entry_ids": [1,2,3],
//   "code": "SALE20"
// }