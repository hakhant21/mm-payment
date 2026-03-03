<?php

namespace Hakhant\Payments\Responses;

use Hakhant\Payments\Responses\BaseResponse;

class RefundResponse extends BaseResponse
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
            'refund_id' => $raw['refund_id'] ?? $raw['out_refund_no'] ?? null,
            'refund_amount' => $raw['refund_amount'] ?? null,
            'refund_status' => $this->mapRefundStatus($raw['refund_status'] ?? $raw['status'] ?? null),
            'refund_time' => $raw['refund_time'] ?? $raw['time_end'] ?? null,
            'refund_reason' => $raw['refund_reason'] ?? null,
            'total_amount' => $raw['total_amount'] ?? null,
        ];
    }

    /**
     * Map gateway refund status to standardized status
     *
     * @param string|null $status
     * @return string|null
     */
    protected function mapRefundStatus(?string $status): ?string
    {
        return match ($status) {
            'SUCCESS', 'REFUND_SUCCESS' => 'success',
            'FAILED', 'REFUND_FAILED' => 'failed',
            'PROCESSING', 'REFUNDING' => 'processing',
            'CLOSED' => 'closed',
            default => $status,
        };
    }

    /**
     * Check if refund is successful
     *
     * @return bool
     */
    public function isRefundSuccessful(): bool
    {
        return $this->data['refund_status'] === 'success';
    }

    /**
     * Check if refund is processing
     *
     * @return bool
     */
    public function isRefundProcessing(): bool
    {
        return $this->data['refund_status'] === 'processing';
    }

    /**
     * Check if refund has failed
     *
     * @return bool
     */
    public function isRefundFailed(): bool
    {
        return $this->data['refund_status'] === 'failed';
    }

    /**
     * Get refund ID
     *
     * @return string|null
     */
    public function getRefundId(): ?string
    {
        return $this->data['refund_id'];
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
     * Get refund amount
     *
     * @return string|null
     */
    public function getRefundAmount(): ?string
    {
        return $this->data['refund_amount'];
    }

    /**
     * Get total amount (original order amount)
     *
     * @return string|null
     */
    public function getTotalAmount(): ?string
    {
        return $this->data['total_amount'];
    }

    /**
     * Get refund time
     *
     * @return string|null
     */
    public function getRefundTime(): ?string
    {
        return $this->data['refund_time'];
    }

    /**
     * Get refund reason
     *
     * @return string|null
     */
    public function getRefundReason(): ?string
    {
        return $this->data['refund_reason'];
    }

    /**
     * Get formatted refund summary
     *
     * @return array
     */
    public function getSummary(): array
    {
        return [
            'status' => $this->data['refund_status'],
            'amount' => $this->data['refund_amount'],
            'transaction_id' => $this->data['transaction_id'],
            'refund_id' => $this->data['refund_id'],
            'time' => $this->data['refund_time'],
        ];
    }
}