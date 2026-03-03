<?php

namespace Hakhant\Payments\Requests;

use Hakhant\Payments\Exceptions\PaymentException;
use Hakhant\Payments\Requests\BaseRequest;

class QueryOrderRequest extends BaseRequest
{
    /**
     * Get the API method for query order
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'kbz.payment.queryorder';
    }

    /**
     * Get the business content for the request
     *
     * @return array
     */
    public function getBizContent(): array
    {
        return array_filter([
            'appid' => $this->appId,
            'merch_code' => $this->merchCode,
            'merch_order_id' => $this->data['merch_order_id'] ?? null,
            'trade_no' => $this->data['trade_no'] ?? null, // Optional: gateway transaction ID
        ]);
    }

    /**
     * Validate the request data
     *
     * @return bool
     * @throws PaymentException
     */
    public function validate(): bool
    {
        // Either merch_order_id or trade_no must be provided
        if (empty($this->data['merch_order_id']) && empty($this->data['trade_no'])) {
            throw new PaymentException('Either merchant order ID or transaction number is required');
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
     * Get transaction number
     *
     * @return string|null
     */
    public function getTradeNo(): ?string
    {
        return $this->data['trade_no'] ?? null;
    }
}