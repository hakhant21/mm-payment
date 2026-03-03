<?php

use Hakhant\Payments\Payment;
use Hakhant\Payments\Gateways\KbzGateway;

it('can initialize a kbz payment gateway', function () {
    $payment = new Payment(new KbzGateway(config('payment')));

    $gateway = $payment->setGateway(new KbzGateway(config('payment')));
    
    expect($gateway)->toBeInstanceOf(Payment::class);
});