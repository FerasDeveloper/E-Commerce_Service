<?php

namespace App\Domains\Payment\Actions;

use App\Domains\Payment\DTOs\RefundDTO;
use App\Domains\Payment\Gateways\PaymentGatewayInterface;
use App\Domains\Payment\Services\PaymentService;

class RefundPaymentAction
{
  public function __construct(
    private readonly PaymentService $service,
  ) {}

  public function execute(RefundDTO $dto, PaymentGatewayInterface $gateway): array
  {
    return $this->service->processRefund($dto, $gateway);
  }
}
