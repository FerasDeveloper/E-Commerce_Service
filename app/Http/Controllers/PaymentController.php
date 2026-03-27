<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Payment\Actions\ProcessPaymentAction;
use App\Domains\Payment\Actions\RefundPaymentAction;
use App\Domains\Payment\DTOs\PaymentDTO;
use App\Domains\Payment\DTOs\RefundDTO;
use App\Domains\Payment\Gateways\PaymentGatewayInterface;
use App\Domains\Payment\Requests\ProcessPaymentRequest;
use App\Domains\Payment\Requests\RefundRequest;
use App\Domains\Payment\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
  public function __construct(
    private readonly PaymentService $service,
    private readonly RefundPaymentAction  $refundPaymentAction,
  ) {}

  // ─── POST /payments ───────────────────────────────────────────────────────

  public function charge(ProcessPaymentRequest $request)
  {
    $dto = PaymentDTO::fromRequest($request);
    $result = $this->service->processPayment($dto);

    if (!$result['success']) {
      return response()->json([
        'message' => 'Payment failed. Please try again.',
        'status'  => $result['status'],
      ], 422);
    }

    return response()->json([
      'message'        => 'Payment processed successfully.',
      'payment_id'     => $result['payment']->id,
      'transaction_id' => $result['transaction_id'],
      'status'         => $result['status'],
    ], 201);
  }

  // ─── POST /payments/{payment}/refund ──────────────────────────────────────

  public function refund(
    RefundRequest           $request,
    PaymentGatewayInterface $gateway,
  ): JsonResponse {
    $dto    = RefundDTO::fromArray($request->validated());
    $result = $this->refundPaymentAction->execute($dto, $gateway);

    if (! $result['success']) {
      return response()->json([
        'message' => 'Refund failed. Please try again.',
        'status'  => $result['status'],
      ], 422);
    }

    return response()->json([
      'message'   => 'Refund processed successfully.',
      'payment_id' => $result['payment']->id,
      'refund_id' => $result['refund_id'],
      'status'    => $result['status'],
    ]);
  }
}
