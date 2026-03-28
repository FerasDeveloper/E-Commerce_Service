<?php

namespace App\Domains\Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'order_id'       => ['required', 'string', 'max:255'],
      'amount'         => ['required', 'numeric', 'min:0.01'],
      'currency'       => ['required', 'string', 'size:3'],
      'gateway'        => ['required', 'string', 'in:stripe,paypal,moyasar,tap'],
      'description'    => ['nullable', 'string', 'max:1000'],
    ];
  }

  public function messages(): array
  {
    return [
      'gateway.in'             => 'Unsupported gateway. Allowed: stripe, paypal.',
      'amount.min'             => 'Amount must be greater than zero.',
      'stripe_token.required'  => 'Stripe token is required when using Stripe.',
      'paypal_nonce.required'  => 'PayPal nonce is required when using PayPal.',
    ];
  }
}
