<?php

namespace Hakhant\Payments\Requests;

use Hakhant\Payments\Exceptions\PaymentException;
use Hakhant\Payments\Requests\BaseRequest;

class PlaceOrderRequest extends BaseRequest
{
    public function getMethod(): string
    {
        return 'kbz.payment.precreate';
    }

    public function getBizContent(): array
    {
        return array_merge([
            'appid' => $this->appId,
            'merch_code' => $this->merchCode,
            'merch_order_id' => $this->data['merch_order_id'] ?? null,
            'trade_type' => $this->data['trade_type'] ?? null,
            'title' => $this->data['title'] ?? null,
            'total_amount' => $this->formatAmount($this->data['total_amount'] ?? 0),
            'trans_currency' => $this->data['trans_currency'] ?? config('payment.defaults.currency', 'CNY'),
            'timeout_express' => $this->data['timeout_express'] ?? config('payment.defaults.timeout_express', '30m'),
        ], array_filter([
            'callback_info' => $this->data['callback_info'] ?? null,
            'trans_type' => $this->data['trans_type'] ?? null,
        ]));
    }

    public function validate(): bool
    {
        $required = ['merch_order_id', 'trade_type', 'total_amount', 'title'];
        
        foreach ($required as $field) {
            if (empty($this->data[$field])) {
                throw new PaymentException("Missing required field: {$field}");
            }
        }

        if ($this->data['total_amount'] <= 0) {
            throw new PaymentException('Total amount must be greater than 0');
        }

        return true;
    }

    protected function formatAmount($amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}