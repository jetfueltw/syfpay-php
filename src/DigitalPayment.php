<?php

namespace Jetfuel\Syfpay;

use Jetfuel\Syfpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const GOODS_NAME = 'GOODS_NAME';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param string $privateKey
     * @param string $publicKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @param string $returnUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl)
    {
        $payload = $this->signPayload([
            'orderNum'        => $tradeNo,
            'random'          => (string)rand(1000, 9999),
            'amount'          => (string)$this->convertYuanToFen($amount),
            'netway'          => $channel,
            'goodsName'       => self::GOODS_NAME,
            'callBackUrl'     => $notifyUrl,
            'callBackViewUrl' => $returnUrl,
        ], $this->publicKey);

        return $this->parseResponse($this->httpClient->post('api/pay', $payload),$this->secretKey);
    }
}
