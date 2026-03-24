<?php

namespace App\Http\Controllers;

use App\Domains\E_Commerce\Requests\CreateCartRequest;
use App\Domains\E_Commerce\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function __construct(private CartService $service) {}

  public function store(CreateCartRequest $request){
    // $dto = 
  }
  }
