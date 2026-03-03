<?php

namespace Hakhant\Payments\Contracts;

interface PaymentGateway
{
    /**
     * Place an order
     *
     * @param array $params
     * @return \Hakhant\Payments\Contracts\PaymentResponse
     */
    public function placeOrder(array $params);

    /**
     * Query an order
     *
     * @param string $orderId
     * @return \Hakhant\Payments\Contracts\PaymentResponse
     */
    public function queryOrder(string $orderId);

    /**
     * Refund an order
     *
     * @param array $params
     * @return \Hakhant\Payments\Contracts\PaymentResponse
     */
    public function refund(array $params);
}