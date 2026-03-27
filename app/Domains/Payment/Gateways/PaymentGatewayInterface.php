<?php

namespace App\Domains\Payment\Gateways;

use App\Domains\Payment\DTOs\PaymentDTO;
use App\Domains\Payment\DTOs\RefundDTO;

interface PaymentGatewayInterface
{
  public function charge(PaymentDTO $dto): array;
  public function refund(RefundDTO $dto): array;
  public function status(string $transactionId): array;
  public function getBalance(): array;
}
