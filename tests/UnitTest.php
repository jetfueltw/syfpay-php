<?php

namespace Test;

use Faker\Factory;

use Jetfuel\Syfpay\BankPayment;
use Jetfuel\Syfpay\Constants\Bank;
use Jetfuel\Syfpay\Constants\Channel;
use Jetfuel\Syfpay\DigitalPayment;
use Jetfuel\Syfpay\TradeQuery;
use Jetfuel\Syfpay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;
    private $merchantPrivateKey;
    private $merchantPayPublicKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_NO');
        $this->secretKey = getenv('MD5_KEY');
        $this->merchantPrivateKey = getenv('MERCHANT_PRIVATE_KEY');
        $this->merchantPayPublicKey = getenv('MERCHANT_PAY_PUBLIC_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = date('YmdHis').rand(10000, 99999);
        $channel = Channel::ALIPAY;
        $amount = 1;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

    $payment = new DigitalPayment($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/);
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl, $returnUrl);

        var_dump($result);

        $this->assertEquals('00', $result['stateCode']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderFind($tradeNo)
    {
        $channel = Channel::ALIPAY;
        $amount = 1;
        $payDate = date('Y-m-d');

    $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/); 
        $result = $tradeQuery->find($tradeNo, $channel, $amount, $payDate);
        

        var_dump($result);
        $this->assertEquals('00', $result['stateCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $channel = Channel::ALIPAY;
        $amount = 1;
        $payDate = date('Y-m-d');

    $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/);
        $result = $tradeQuery->isPaid($tradeNo, $channel, $amount, $payDate);

        var_dump($result);

        $this->assertFalse($result);
    }

    /* We cannot test it without syfpay's public key*/

    // public function testNotifyWebhookVerifyNotifyPayload()
    //     {
    //         $mock = $this->getMockForTrait(NotifyWebhook::class);

    //         $payload = [
    //             'data'          => 'VwbywBPocHXUAKOSly8w%2BvqGRNHg%2FfioIvUTj644ta1wQ6qKjxBSSMPKGHIN3wJYst4bJrQygoAj%0D%0AF88V8hllQUCCh28uHs7GvUp4cezBCNoVDkiNQ9DN2xvuam4lYlp1xXeuyAPDWtPHg3Q7qtxFivNC%0D%0AJDBA9vIc2pq1P997MjqCcoFi4uILZWJZdDJwfIZnYeHo%2F84KMPuVjmNkKQ7eIXXmMvp03OAzW%2BJN%0D%0AyH%2BAtjxBaPueTrFQgQeirdiplaWbYBtez4gdACmC25b6MkaoPdx671%2FnkUPvqOKQWy5b74EZPDCw%0D%0ALCEis4jZ3%2BgU5jSjGnrk%2BVFPJ4DJwFitserT%2Bw%3D%3D',
    //             'merchNo'       => 'SYF201803080000',
    //             'orderNum'         => '20170812104118797WlN',
    //         ];

    //         $this->assertTrue($mock->verifyNotifyPayload($payload, $this->merchantPrivateKey, $this->secretKey));
    //     }
    // public function testNotifyWebhookParseNotifyPayload()
    //     {
    //         $mock = $this->getMockForTrait(NotifyWebhook::class);

    //         $payload = [
    //             'data'          => 'VwbywBPocHXUAKOSly8w%2BvqGRNHg%2FfioIvUTj644ta1wQ6qKjxBSSMPKGHIN3wJYst4bJrQygoAj%0D%0AF88V8hllQUCCh28uHs7GvUp4cezBCNoVDkiNQ9DN2xvuam4lYlp1xXeuyAPDWtPHg3Q7qtxFivNC%0D%0AJDBA9vIc2pq1P997MjqCcoFi4uILZWJZdDJwfIZnYeHo%2F84KMPuVjmNkKQ7eIXXmMvp03OAzW%2BJN%0D%0AyH%2BAtjxBaPueTrFQgQeirdiplaWbYBtez4gdACmC25b6MkaoPdx671%2FnkUPvqOKQWy5b74EZPDCw%0D%0ALCEis4jZ3%2BgU5jSjGnrk%2BVFPJ4DJwFitserT%2Bw%3D%3D',
    //             'merchNo'       => 'SYF201803080000',
    //             'orderNum'      => '20170812104118797WlN',
    //         ];
    //         $this->assertEquals('00',$mock->parseNotifyPayload($payload, $this->merchantPrivateKey, $this->secretKey)['payResult']);
    //     }


    // public function testNotifyWebhookSuccessNotifyResponse()
    //     {
    //         $mock = $this->getMockForTrait(NotifyWebhook::class);

    //         $this->assertEquals('0', $mock->successNotifyResponse());
    //     }
}
