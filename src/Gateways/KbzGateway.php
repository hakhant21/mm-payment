<?php

namespace Hakhant\Payments\Gateways;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hakhant\Payments\Contracts\PaymentGateway;
use Hakhant\Payments\Contracts\PaymentResponse;
use Hakhant\Payments\Exceptions\PaymentException;
use Hakhant\Payments\Requests\PlaceOrderRequest;
use Hakhant\Payments\Requests\QueryOrderRequest;
use Hakhant\Payments\Requests\RefundRequest;
use Hakhant\Payments\Responses\PlaceOrderResponse;
use Hakhant\Payments\Responses\QueryOrderResponse;
use Hakhant\Payments\Responses\RefundResponse;
use Illuminate\Support\Facades\Log;


class KbzGateway implements PaymentGateway
{
    protected array $config;
    protected Client $httpClient;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->httpClient = $this->createHttpClient();
    }

    protected function createHttpClient(): Client
    {
        $options = [
            'timeout' => $this->config['http']['timeout'] ?? 30,
            'connect_timeout' => $this->config['http']['connect_timeout'] ?? 10,
            'verify' => $this->config['http']['verify_ssl'] ?? true,
        ];

        if (!empty($this->config['ssl']['cert_path'])) {
            $options['cert'] = $this->config['ssl']['cert_path'];
        }

        if (!empty($this->config['ssl']['key_path'])) {
            $options['ssl_key'] = [
                $this->config['ssl']['key_path'],
                $this->config['ssl']['key_password'] ?? ''
            ];
        }

        return new Client($options);
    }

    public function placeOrder(array $params): PaymentResponse
    {
        try {
            $request = new PlaceOrderRequest($params);
            $request->setAppId($this->config['app_id']);
            $request->setMerchCode($this->config['merchant_code']);
            $request->setNotifyUrl($this->config['notify_url'] ?? '');

            Log::info('Processing place order', [
                'order_id' => $params['merch_order_id'] ?? null,
                'amount' => $params['total_amount'] ?? null,
            ]);

            $response = $this->sendRequest(
                $this->config['endpoints']['place_order'],
                $request
            );

            return new PlaceOrderResponse($response);
        } catch (Exception $e) {
            Log::error('Place order failed', [
                'order_id' => $params['merch_order_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new PaymentException(
                'Place order failed: ' . $e->getMessage(),
                (int) $e->getCode(),
                ['params' => $params, 'error' => $e->getMessage()]
            );
        }
    }

    public function queryOrder(string $orderId): PaymentResponse
    {
        try {
            $request = new QueryOrderRequest(['merch_order_id' => $orderId]);
            $request->setAppId($this->config['app_id']);
            $request->setMerchCode($this->config['merchant_code']);
            $request->setVersion('1.0');

            Log::info('Processing query order', ['order_id' => $orderId]);

            $response = $this->sendRequest(
                $this->config['endpoints']['query_order'],
                $request
            );

            return new QueryOrderResponse($response);
        } catch (Exception $e) {
            Log::error('Query order failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new PaymentException(
                'Query order failed: ' . $e->getMessage(),
                (int) $e->getCode(),
                ['order_id' => $orderId, 'error' => $e->getMessage()]
            );
        }
    }

    public function refund(array $params): PaymentResponse
    {
        try {
            $request = new RefundRequest($params);
            $request->setAppId($this->config['app_id']);
            $request->setMerchCode($this->config['merchant_code']);

            Log::info('Processing refund', [
                'order_id' => $params['merch_order_id'] ?? null,
                'amount' => $params['refund_amount'] ?? null,
            ]);

            $response = $this->sendRequest(
                $this->config['endpoints']['refund'],
                $request
            );

            return new RefundResponse($response);
        } catch (Exception $e) {
            Log::error('Refund failed', [
                'order_id' => $params['merch_order_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new PaymentException(
                'Refund failed: ' . $e->getMessage(),
                (int) $e->getCode(),
                ['params' => $params, 'error' => $e->getMessage()]
            );
        }
    }

    protected function sendRequest(string $url, $request): array
    {
        $data = $this->buildRequestData($request);
        
        Log::debug('Sending payment request', [
            'url' => $url,
            'data' => $data,
        ]);

        try {
            $response = $this->httpClient->post($url, [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::debug('Received payment response', [
                'url' => $url,
                'response' => $result,
            ]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('HTTP request failed', [
                    'url' => $url,
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
            ]);
            
            throw new PaymentException(
                'HTTP request failed: ' . $e->getMessage(), 
                (int) $e->getCode()
            );
        }
    }

    protected function buildRequestData($request): array
    {
        $request->validate();
        
        $data = $request->toArray();
        $data['biz_content'] = json_encode($request->getBizContent(), JSON_UNESCAPED_UNICODE);
        $data['sign'] = $this->generateSign($data);

        return $data;
    }

    protected function generateSign(array $data): string
    {
        $signData = $data;
        unset($signData['sign']);
        
        ksort($signData);
        
        $string = urldecode(http_build_query($signData)) . '&key=' . $this->config['merchant_key'];
        
        return strtoupper(hash('sha256', $string));
    }
}