<?php

namespace Hakhant\Payments\Responses;

use Hakhant\Payments\Contracts\PaymentResponse;

abstract class BaseResponse implements PaymentResponse
{
    protected array $data;
    protected array $raw;

    public function __construct(array $raw)
    {
        $this->raw = $raw;
        $this->data = $this->parse($raw);
    }

    abstract protected function parse(array $raw): array;

    public function isSuccessful(): bool
    {
        $code = $this->data['code'] ?? '';
        return $code === 'SUCCESS' || $code === '10000';
    }

    public function getMessage(): ?string
    {
        return $this->data['message'] ?? null;
    }

    public function getCode(): ?string
    {
        return $this->data['code'] ?? null;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getRaw(): array
    {
        return $this->raw;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }
}