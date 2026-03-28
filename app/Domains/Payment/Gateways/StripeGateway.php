<?php

namespace App\Domains\Payment\Gateways;

use App\Domains\Payment\DTOs\PaymentDTO;
use App\Domains\Payment\DTOs\RefundDTO;
use Illuminate\Support\Facades\Log;
use Stripe\Balance;
use Stripe\Charge;
use Stripe\Refund;
use Stripe\Stripe;

class StripeGateway implements PaymentGatewayInterface
{
  public function __construct()
  {
    Stripe::setApiKey(config('payment.gateways.stripe.secret_key'));
  }

  // ─── Charge ───────────────────────────────────────────────────────────────

  public function charge(PaymentDTO $dto): array
  {
    try {
      $charge = Charge::create([
        'amount'      => (int) round($dto->amount * 100),
        'currency'    => strtolower($dto->currency),
        // 'source'      => $dto->gatewayToken,
        'source'      => "tok_visa",
        'description' => $dto->description ?? "Order #{$dto->orderId}",
        'metadata'    => [
          'order_id'      => $dto->orderId,
          'customer_name' => $dto->userName,
        ],
      ]);

      if ($charge->status === 'succeeded') {
        return [
          'success'        => true,
          'transaction_id' => $charge->id,
          'status'         => $charge->status,
          'raw'            => $charge->toArray(),
        ];
      }

      return [
        'success'        => false,
        'transaction_id' => $charge->id ?? '',
        'status'         => $charge->status,
        'raw'            => $charge->toArray(),
      ];
    } catch (\Exception $e) {
      Log::error('Stripe charge exception', ['error' => $e->getMessage()]);

      return [
        'success'        => false,
        'transaction_id' => '',
        'status'         => 'failed',
        'raw'            => ['error' => $e->getMessage()],
      ];
    }
  }

  // ─── Refund ───────────────────────────────────────────────────────────────

  public function refund(RefundDTO $dto): array
  {
    try {
      $refund = Refund::create([
        'charge'   => $dto->transactionId,
        'amount'   => (int) round($dto->amount * 100),
        'reason'   => $this->mapRefundReason($dto->reason),
        'metadata' => $dto->metadata,
      ]);

      return [
        'success'   => $refund->status === 'succeeded',
        'refund_id' => $refund->id,
        'status'    => $refund->status,
        'raw'       => $refund->toArray(),
      ];
    } catch (\Exception $e) {
      Log::error('Stripe refund exception', ['error' => $e->getMessage()]);

      return [
        'success'   => false,
        'refund_id' => '',
        'status'    => 'failed',
        'raw'       => ['error' => $e->getMessage()],
      ];
    }
  }

  // ─── Status ───────────────────────────────────────────────────────────────

  public function status(string $transactionId): array
  {
    try {
      $charge = Charge::retrieve($transactionId);

      return [
        'status' => $charge->status,
        'raw'    => $charge->toArray(),
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'unknown',
        'raw'    => ['error' => $e->getMessage()],
      ];
    }
  }

  // ─── Balance ──────────────────────────────────────────────────────────────

  public function getBalance(): array
  {
    $balance = Balance::retrieve();

    return [
      'success' => true,
      'balance' => $balance->available[0]->amount / 100,
    ];
  }

  // ─── Helpers ──────────────────────────────────────────────────────────────

  private function mapRefundReason(?string $reason): string
  {
    return match (true) {
      str_contains((string) $reason, 'duplicate')  => 'duplicate',
      str_contains((string) $reason, 'fraudulent') => 'fraudulent',
      default                                       => 'requested_by_customer',
    };
  }
}
