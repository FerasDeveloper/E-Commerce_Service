<?php

namespace App\Domains\E_Commerce\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'item_id' => 'required',
      'count' => ['required', 'numeric'],
      'price' => ['required', 'numeric']
    ];
  }
}
