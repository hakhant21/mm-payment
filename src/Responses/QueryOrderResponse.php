<?php

namespace Hakhant\Payments\Responses;

use Hakhant\Payments\Responses\BaseResponse;

class QueryOrderResponse extends BaseResponse
{
    /**
     * Parse the raw response data
     *
     * @param array $raw
     * @return array
     */
    protected function parse(array $raw): array
    {
        return [
            'code' => $raw['resp_code'] ?? $raw['code'] ?? null,
            'message' => $raw['resp_msg'] ?? $raw['message'] ?? null,
            'transaction_id' => $raw['trade_no'] ?? null,
            'order_id' => $raw['out_trade_no'] ?? null,
            'merch_order_id' => $raw['merch_order_id'] ?? null,
            'amount' => $raw['total_amount'] ?? null,
            'status' => $this->mapStatus($raw['trade_status'] ?? $raw['status'] ?? null),
            'pay_time' => $raw['time_end'] ?? $raw['pay_time'] ?? null,
            'buyer_id' => $raw['buyer_id'] ?? null,
            'buyer_account' => $raw['buyer_account'] ?? null,
            'attach' => $raw['attach'] ?? null,
            'detail' => $raw['detail'] ?? null,
        ];
    }

    /**
     * Map gateway status to standardized status
     *
     * @param string|null $status
     * @return string|null
     */
    protected function mapStatus(?string $status): ?string
    {
        return match ($status) {
            'SUCCESS', 'TRADE_SUCCESS', 'TRADE_FINISHED' => 'success',
            'FAILED', 'TRADE_CLOSED' => 'failed',
            'WAIT_BUYER_PAY', 'NOTPAY' => 'pending',
            'REFUND', 'REFUNDING' => 'refunding',
            'REFUNDED' => 'refunded',
            default => $status,
        };
    }

    /**
     * Check if the order is paid
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return in_array($this->data['status'], ['success', 'refunded']);
    }

    /**
     * Check if the order is pending payment
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->data['status'] === 'pending';
    }

    /**
     * Check if the order has failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->data['status'] === 'failed';
    }

    /**
     * Check if the order is refunded
     *
     * @return bool
     */
    public function isRefunded(): bool
    {
        return $this->data['status'] === 'refunded';
    }

    /**
     * Get transaction ID
     *
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->data['transaction_id'];
    }

    /**
     * Get order ID
     *
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->data['order_id'];
    }

    /**
     * Get merchant order ID
     *
     * @return string|null
     */
    public function getMerchOrderId(): ?string
    {
        return $this->data['merch_order_id'];
    }

    /**
     * Get payment amount
     *
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->data['amount'];
    }

    /**
     * Get payment time
     *
     * @return string|null
     */
    public function getPayTime(): ?string
    {
        return $this->data['pay_time'];
    }

    /**
     * Get buyer information
     *
     * @return array|null
     */
    public function getBuyerInfo(): ?array
    {
        if (!$this->data['buyer_id'] && !$this->data['buyer_account']) {
            return null;
        }

        return [
            'id' => $this->data['buyer_id'],
            'account' => $this->data['buyer_account'],
        ];
    }
}