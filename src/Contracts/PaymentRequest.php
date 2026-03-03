<?php

namespace Hakhant\Payments\Contracts;

interface PaymentRequest
{
    /**
     * Get the request method
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get the request data as array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Get the biz content for the request
     *
     * @return array
     */
    public function getBizContent(): array;

    /**
     * Validate the request
     *
     * @return bool
     * @throws \Hakhant\Payments\Exceptions\PaymentException
     */
    public function validate(): bool;
}