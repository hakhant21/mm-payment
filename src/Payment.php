<?php

namespace Hakhant\Payments;

use Hakhant\Payments\Contracts\PaymentGateway;
use Hakhant\Payments\Contracts\PaymentResponse;
use Hakhant\Payments\Exceptions\PaymentException;

class Payment
{
    protected PaymentGateway $gateway;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Place an order
     *
     * @param array $params
     * @return PaymentResponse
     * @throws PaymentException
     */
    public function placeOrder(array $params): PaymentResponse
    {
        return $this->gateway->placeOrder($params);
    }

    /**
     * Query an order
     *
     * @param string $orderId
     * @return PaymentResponse
     * @throws PaymentException
     */
    public function queryOrder(string $orderId): PaymentResponse
    {
        return $this->gateway->queryOrder($orderId);
    }

    /**
     * Refund an order
     *
     * @param array $params
     * @return PaymentResponse
     * @throws PaymentException
     */
    public function refund(array $params): PaymentResponse
    {
        return $this->gateway->refund($params);
    }

    /**
     * Set a different gateway
     *
     * @param PaymentGateway $gateway
     * @return $this
     */
    public function setGateway(PaymentGateway $gateway): self
    {
        $this->gateway = $gateway;
        return $this;
    }
}
