<?php

namespace Hakhant\Payments\Contracts;

interface PaymentResponse
{
    /**
     * Check if response is successful
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Get response message
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Get response code
     *
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * Get all response data
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Get raw response data
     *
     * @return array
     */
    public function getRaw(): array;
}