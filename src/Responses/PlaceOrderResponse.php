<?php

namespace Hakhant\Payments\Responses;

use Hakhant\Payments\Responses\BaseResponse;

class PlaceOrderResponse extends BaseResponse
{
    protected function parse(array $raw): array
    {
        return [
            'code' => $raw['resp_code'] ?? $raw['code'] ?? null,
            'message' => $raw['resp_msg'] ?? $raw['message'] ?? null,
            'transaction_id' => $raw['trade_no'] ?? null,
            'order_id' => $raw['out_trade_no'] ?? null,
            'amount' => $raw['total_amount'] ?? null,
            'status' => $raw['trade_status'] ?? null,
            'payment_info' => $raw['payment_info'] ?? null,
        ];
    }

    public function getTransactionId(): ?string
    {
        return $this->data['transaction_id'];
    }

    public function getPaymentInfo(): ?array
    {
        return $this->data['payment_info'];
    }
}