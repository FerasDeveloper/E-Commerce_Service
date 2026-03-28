<?php

namespace App\Domains\Payment\DTOs;

use App\Domains\Payment\Requests\ProcessPaymentRequest;

class PaymentDTO
{
  public function __construct(
    public readonly int  $orderId,
    public readonly int  $userId,
    public readonly string $userName,
    public readonly int  $projectId,
    public readonly float   $amount,
    public readonly string  $currency,
    public readonly string  $gateway,
    public readonly ?string $description  = null
  ) {}

  public static function fromRequest(ProcessPaymentRequest $request): self
  {
    return new self(
      orderId: (int) $request->order_id,
      projectId: $request->project_id,
      userId: $request->attributes->get('auth_user')['id'],
      userName: $request->attributes->get('auth_user')['name'],
      amount: (float) $request->amount,
      // currency: strtoupper($request->currency ?? 'SEK'),
      currency: strtoupper('SEK'),
      gateway: $request->gateway,
      description: $request->description ?? null,
    );
  }

  public function toArray(): array
  {
    return [
      'order_id'       => $this->orderId,
      'project_id'       => $this->projectId,
      'user_id'       => $this->userId,
      'amount'         => $this->amount,
      'currency'       => $this->currency,
      'gateway'        => $this->gateway,
      'description'    => $this->description,
      // gatewayToken مقصود عدم إرجاعه هنا لأسباب أمنية
    ];
  }
}
