<?php

namespace App\Domains\Payment\Gateways;

use App\Domains\Payment\DTOs\PaymentDTO;
use App\Domains\Payment\DTOs\RefundDTO;
use Braintree\Gateway;
use Braintree\Transaction;
use Braintree\TransactionSearch;
use Illuminate\Support\Facades\Log;

class PaypalGateway implements PaymentGatewayInterface
{
  private Gateway $gateway;

  public function __construct()
  {
    $this->gateway = new Gateway([
      'environment' => config('payment.gateways.paypal.environment'),
      'merchantId'  => config('payment.gateways.paypal.merchant_id'),
      'publicKey'   => config('payment.gateways.paypal.public_key'),
      'privateKey'  => config('payment.gateways.paypal.private_key'),
    ]);
  }

  // ─── Charge ───────────────────────────────────────────────────────────────

  public function charge(PaymentDTO $dto): array
  {
    try {
      $result = $this->gateway->transaction()->sale([
        'amount'             => number_format($dto->amount, 2, '.', ''),
        // 'paymentMethodNonce' => $dto->gatewayToken,
        'paymentMethodNonce' => "fake-valid-nonce",
        'orderId'            => $dto->orderId,
        'customer'           => [
          'username' => $dto->userName,
          // 'email'     => $dto->customerEmail,
        ],
        'options' => [
          'submitForSettlement' => true,
        ],
      ]);

      if ($result->success) {
        return [
          'success'        => true,
          'transaction_id' => $result->transaction->id,
          'status'         => $result->transaction->status,
          'raw'            => (array) $result->transaction,
        ];
      }

      Log::warning('Braintree charge failed', ['message' => $result->message]);

      return [
        'success'        => false,
        'transaction_id' => '',
        'status'         => 'failed',
        'raw'            => ['error' => $result->message],
      ];
    } catch (\Exception $e) {
      Log::error('Braintree charge exception', ['error' => $e->getMessage()]);

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
      $result = $this->gateway->transaction()->refund(
        $dto->transactionId,
        number_format($dto->amount, 2, '.', ''),
      );

      if ($result->success) {
        return [
          'success'   => true,
          'refund_id' => $result->transaction->id,
          'status'    => $result->transaction->status,
          'raw'       => (array) $result->transaction,
        ];
      }

      Log::warning('Braintree refund failed', ['message' => $result->message]);

      return [
        'success'   => false,
        'refund_id' => '',
        'status'    => 'failed',
        'raw'       => ['error' => $result->message ?? 'Refund failed'],
      ];
    } catch (\Exception $e) {
      Log::error('Braintree refund exception', ['error' => $e->getMessage()]);

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
      $transaction = $this->gateway->transaction()->find($transactionId);

      return [
        'status' => $transaction->status,
        'raw'    => (array) $transaction,
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
    $collection = $this->gateway->transaction()->search([
      TransactionSearch::status()->is(Transaction::SETTLED),
    ]);

    $total = 0.0;
    foreach ($collection as $transaction) {
      $total += floatval($transaction->amount);
    }

    return [
      'success'             => true,
      'settled_balance_usd' => number_format($total, 2, '.', ''),
    ];
  }
}
