<?php

namespace Hakhant\Payments\Requests;

use Hakhant\Payments\Contracts\PaymentRequest;

abstract class BaseRequest implements PaymentRequest
{
    protected array $data = [];
    protected string $appId = '';
    protected string $merchCode = '';
    protected string $notifyUrl = '';
    protected string $version = '3.0';
    protected int $timestamp;
    protected string $nonceStr;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->timestamp = time();
        $this->nonceStr = $this->generateNonce();
        $this->version = config('payment.defaults.version', '3.0');
    }

    protected function generateNonce(int $length = 32): string
    {
        return strtoupper(hash('sha256', uniqid((string) mt_rand(), true))) . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length - 64);
    }

    public function toArray(): array
    {
        return array_filter([
            'timestamp' => $this->timestamp,
            'nonce_str' => $this->nonceStr,
            'method' => $this->getMethod(),
            'version' => $this->version,
            'notify_url' => $this->notifyUrl,
        ]);
    }

    public function setAppId(string $appId): self
    {
        $this->appId = $appId;
        return $this;
    }

    public function setMerchCode(string $merchCode): self
    {
        $this->merchCode = $merchCode;
        return $this;
    }

    public function setNotifyUrl(string $notifyUrl): self
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }
}