<?php

use Hakhant\Payments\Contracts\PaymentResponse;
use Hakhant\Payments\Facades\Payment;

it('can be placed an order and get a response', function () {
    $payment = Payment::placeOrder([
        'appid' => 'test_app_id',
        'merch_order_id' => 'test_order_id',
        'trade_type' => 'PAY_BY_QRCODE',
        'total_amount' => 100,
        'trans_currency' => 'MMK',
        'title' => 'Test payment',
        'timeout_express' => 15,
    ]);

    expect($payment)->toBeInstanceOf(PaymentResponse::class);
});