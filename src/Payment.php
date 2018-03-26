<?php

namespace Jetfuel\Syfpay;

use Jetfuel\Syfpay\HttpClient\CurlHttpClient;
use Jetfuel\Syfpay\Traits\ConvertMoney;

use Jetfuel\Syfpay\Constants\BaseUrl;

class Payment
{
    use ConvertMoney;

    //const BASE_API_URL = 'http://139.199.195.194:8080/';
    const API_VERSION  = 'V3.1.0.0';
    const CHARSET      = 'UTF-8';

    /**
     * @var string
     */
    protected $merchantNo;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Syfpay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey for sign
     * @param string $privateKey
     * @param string $publicKey
     * @param string $remitRublicKey
     * @param string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $privateKey, $publicKey, $baseApiUrl = null)
    {
        $this->merchantNo = $merchantId;
        $this->secretKey = $secretKey;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->baseApiUrl = $baseApiUrl;
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @param string $publicKey
     * @return string
     */
    protected function signPayload(array $payload, $publicKey)
    {
        $payload['merNo'] = $this->merchantNo;
        $payload['version'] = self::API_VERSION;
        $payload['charset'] = self::CHARSET;
        if (isset($this->baseApiUrl))
        {
            $this->httpClient = new CurlHttpClient($this->baseApiUrl);
        }
        else
        {
            $this->httpClient = new CurlHttpClient(BaseUrl::URL[$payload['netway']]);
        }
        ksort($payload);
        $payload['sign'] = Signature::generate($payload, $this->secretKey);
        $data = RsaCrypt::rsaEncrypt($payload, $publicKey);

        return 'data='.$data.'&merchNo='.$this->merchantNo.'&version='.self::API_VERSION;
    }

    protected function signQueryPayload(array $payload, $publicKey)
    {
        $payload['merNo'] = $this->merchantNo;
        if (isset($this->baseApiUrl))
        {
            $this->httpClient = new CurlHttpClient($this->baseApiUrl);
        }
        else
        {
            $this->httpClient = new CurlHttpClient(BaseUrl::QUERY);
        }
        ksort($payload);
        $payload['sign'] = Signature::generate($payload, $this->secretKey);
        $data = RsaCrypt::rsaEncrypt($payload, $publicKey);
        
        return 'data='.$data.'&merchNo='.$this->merchantNo.'&version='.self::API_VERSION;
    }
}
