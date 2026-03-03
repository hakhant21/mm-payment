<?php

namespace Hakhant\Payments\Requests;

use Hakhant\Payments\Exceptions\PaymentException;
use Hakhant\Payments\Requests\BaseRequest;

class RefundRequest extends BaseRequest
{
    /**
     * Get the API method for refund
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'kbz.payment.refund';
    }

    /**
     * Get the business content for the request
     *
     * @return array
     */
    public function getBizContent(): array
    {
        return array_merge([
            'appid' => $this->appId,
            'merch_code' => $this->merchCode,
            'merch_order_id' => $this->data['merch_order_id'] ?? null,
            'refund_amount' => $this->formatAmount($this->data['refund_amount'] ?? 0),
            'refund_reason' => $this->data['refund_reason'] ?? null,
        ], array_filter([
            'trade_no' => $this->data['trade_no'] ?? null,
            'out_refund_no' => $this->data['out_refund_no'] ?? null,
            'refund_currency' => $this->data['refund_currency'] ?? null,
            'notify_url' => $this->data['refund_notify_url'] ?? null,
            'operator_id' => $this->data['operator_id'] ?? null,
            'store_id' => $this->data['store_id'] ?? null,
            'terminal_id' => $this->data['terminal_id'] ?? null,
        ]));
    }

    /**
     * Validate the request data
     *
     * @return bool
     * @throws PaymentException
     */
    public function validate(): bool
    {
        // Check required fields
        $required = ['merch_order_id', 'refund_amount'];
        
        foreach ($required as $field) {
            if (empty($this->data[$field])) {
                throw new PaymentException("Missing required field: {$field}");
            }
        }

        // Validate refund amount
        if ($this->data['refund_amount'] <= 0) {
            throw new PaymentException('Refund amount must be greater than 0');
        }

        // Validate refund amount format (max 2 decimal places)
        $amount = (string) $this->data['refund_amount'];
        if (preg_match('/\.\d{3,}$/', $amount)) {
            throw new PaymentException('Refund amount cannot have more than 2 decimal places');
        }

        // Validate refund reason length if provided
        if (!empty($this->data['refund_reason']) && strlen($this->data['refund_reason']) > 255) {
            throw new PaymentException('Refund reason cannot exceed 255 characters');
        }

        return true;
    }

    /**
     * Set transaction number (gateway transaction ID)
     *
     * @param string $tradeNo
     * @return $this
     */
    public function setTradeNo(string $tradeNo): self
    {
        $this->data['trade_no'] = $tradeNo;
        return $this;
    }

    /**
     * Set external refund number
     *
     * @param string $outRefundNo
     * @return $this
     */
    public function setOutRefundNo(string $outRefundNo): self
    {
        $this->data['out_refund_no'] = $outRefundNo;
        return $this;
    }

    /**
     * Set refund currency
     *
     * @param string $currency
     * @return $this
     */
    public function setRefundCurrency(string $currency): self
    {
        $this->data['refund_currency'] = $currency;
        return $this;
    }

    /**
     * Set refund notify URL
     *
     * @param string $url
     * @return $this
     */
    public function setRefundNotifyUrl(string $url): self
    {
        $this->data['refund_notify_url'] = $url;
        return $this;
    }

    /**
     * Set operator ID
     *
     * @param string $operatorId
     * @return $this
     */
    public function setOperatorId(string $operatorId): self
    {
        $this->data['operator_id'] = $operatorId;
        return $this;
    }

    /**
     * Get merchant order ID
     *
     * @return string|null
     */
    public function getMerchOrderId(): ?string
    {
        return $this->data['merch_order_id'] ?? null;
    }

    /**
     * Get refund amount
     *
     * @return float|null
     */
    public function getRefundAmount(): ?float
    {
        return $this->data['refund_amount'] ?? null;
    }

    /**
     * Get refund reason
     *
     * @return string|null
     */
    public function getRefundReason(): ?string
    {
        return $this->data['refund_reason'] ?? null;
    }
}