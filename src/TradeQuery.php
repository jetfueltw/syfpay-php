<?php

namespace Jetfuel\Syfpay;

use Jetfuel\Syfpay\Traits\ResultParser;

class TradeQuery extends Payment
{
    use ResultParser;
    const GOODS_NAME          = 'GOODS_NAME';

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
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @param string $channel
     * @param string $amount
     * @param string $payDate
     * @return array|
     */
    public function find($tradeNo, $channel, $amount, $payDate)
    {
        $payload = $this->signQueryPayload([
            'orderNum'          => $tradeNo,
            'netway'            => $channel,
            'amount'            => (string)$this->convertYuanToFen($amount),
            'goodsName'         => self::GOODS_NAME,
            'payDate'           => $payDate,

        ], $this->publicKey);

        $order = $this->parseResponse($this->httpClient->post('api/queryPayResult', $payload), $this->secretKey);

        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @return bool
     */
    public function isPaid($tradeNo, $channel, $amount, $payDate)
    {
        $order = $this->find($tradeNo, $channel, $amount, $payDate);

        if ($order['payStateCode'] == '00') 
        {
            return true;
        }

        return false;
    }
}
