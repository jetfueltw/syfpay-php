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
        $channel = Channel::UNIONPAY;
        $amount = 1;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/);
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl, $returnUrl);

        // var_dump($result);

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
        $channel = Channel::UNIONPAY;
        $amount = 1;
        $payDate = date('Y-m-d');

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/); 
        $result = $tradeQuery->find($tradeNo, $channel, $amount, $payDate);

        // var_dump($result);

        $this->assertEquals('00', $result['stateCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $channel = Channel::UNIONPAY;
        $amount = 1;
        $payDate = date('Y-m-d');

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, $this->merchantPrivateKey, $this->merchantPayPublicKey/*, 'http://wx.637pay.com/'*/);
        $result = $tradeQuery->isPaid($tradeNo, $channel, $amount, $payDate);

        // var_dump($result);

        $this->assertFalse($result);
    }

    /* We cannot test it without syfpay's public key*/

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'data'          => 'aj0eeK+Bli058HoX/aMz5yac4aQcFlO7FkAR3OjoIG3ssRSSDliUDMrMER4rO7VTKe6UhToVTxUP4HE/NMbns0C1wTyu9qS+mHrviR1TS0rB2P+KAfl/kmH99CW8+kCL5itP2kujPgZPR8CuqeW3LxzdHuSGoqAWnsRND7tGVPty/pIeI9kcu5/m5kYF7OWtQZxceFEbuXKEqaV5GD1w02eQbPUADHbAyJ/gYkittsOrIxXJk/Hc5dImHv9+YJuDUe2OygTQcYljvziGQ6KpeVeKeqjWqyAUz8xExobPE2oaxIOY/DKARbD1N7O5lmBq8ZLb8KjSzRvODIOGB8Tg0w==',
            'merchNo'       => 'SYF201803260287',
            'orderNum'         => '201804021447049639',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey, $this->merchantPrivateKey));
    }

    public function testNotifyWebhookParseNotifyPayload()
        {
            $mock = $this->getMockForTrait(NotifyWebhook::class);

            $payload = [
                'data'          => 'aj0eeK+Bli058HoX/aMz5yac4aQcFlO7FkAR3OjoIG3ssRSSDliUDMrMER4rO7VTKe6UhToVTxUP4HE/NMbns0C1wTyu9qS+mHrviR1TS0rB2P+KAfl/kmH99CW8+kCL5itP2kujPgZPR8CuqeW3LxzdHuSGoqAWnsRND7tGVPty/pIeI9kcu5/m5kYF7OWtQZxceFEbuXKEqaV5GD1w02eQbPUADHbAyJ/gYkittsOrIxXJk/Hc5dImHv9+YJuDUe2OygTQcYljvziGQ6KpeVeKeqjWqyAUz8xExobPE2oaxIOY/DKARbD1N7O5lmBq8ZLb8KjSzRvODIOGB8Tg0w==',
                'merchNo'       => 'SYF201803260287',
                'orderNum'         => '201804021447049639',    
            ];
    
            $this->assertEquals('00',$mock->parseNotifyPayload($payload, $this->secretKey, $this->merchantPrivateKey)['payResult']);
        }


    public function testNotifyWebhookSuccessNotifyResponse()
        {
            $mock = $this->getMockForTrait(NotifyWebhook::class);

            $this->assertEquals('0', $mock->successNotifyResponse());
        }
}
