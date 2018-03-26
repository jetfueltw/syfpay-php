## 介紹

輕易付聚合支付 PHP 版本封裝。

## 安裝

使用 Composer 安裝。

```
composer require jetfueltw/syfpay-php
```

## 使用方法

### 掃碼支付下單

使用微信支付、QQ錢包、支付寶掃碼支付，下單後返回支付網址，請自行轉為 QR Code。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$merchantPrivateKey = '-----BEGIN RSA PRIVATE KEY-----XXXXXXXXXX-----BEGIN RSA PRIVATE KEY-----' //RSA密鑰
$merchantPayPublicKey = '-----BEGIN PUBLIC KEY-----XXXXXXXXXX-----BEGIN PUBLIC KEY-----' //RSA公鑰
$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 支付通道，支援微信支付、QQ錢包、支付寶
$amount = 1.00; // 消費金額 (元)
$clientIp = 'XXX.XXX.XXX.XXX'; // 消費者端 IP 位址
$notifyUrl = 'https://XXX.XXX.XXX'; // 交易完成後異步通知接口
$returnUrl = 'https://XXX.XXX.XXX'; // 交易完成後會跳轉到這個頁面
```
```
$payment = new DigitalPayment(merchantId, secretKey, merchantPrivateKey, merchantPayPublicKey);
$result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl);
```
```
Result:
[
    'merNo' => 'XXXXXXXXXXXXXXX', // 商家號
    'stateCode' => 'XX', // 00表示成功
    'msg' => 'XXXX'; // 狀態描述
    'orderNum' =>'20180109023351XXXXX', // 商家產生的唯一訂單號
    'qrcodeUrl' =>'https://qr.alipay.com/upx07533duhp4xmuuXXXXXXX', // 支付網址
    'sign' => '1C1E6B6DCD8DC9F70565AFXXXXXXXXXX', // 簽名(字母大寫)
];
```

### 掃碼支付交易成功通知

消費者支付成功後，平台會發出 HTTP POST 請求到你下單時填的 $notifyUrl，商家在收到通知並處理完後必須回應 `0`，否則平台會認為通知失敗，並間隔5分鐘再次推送，推送5次。

* 商家必需正確處理重複通知的情況。
* 能使用 `NotifyWebhook@successNotifyResponse` 返回成功回應。  
* 務必使用 `NotifyWebhook@verifyNotifyPayload` 驗證簽證是否正確。
* 通知的消費金額單位為 `分`，使用 `NotifyWebhook@parseNotifyPayload` 能驗證簽證並把消費金額單位轉為 `元`。 

```
Post Data:
[
    'merNo' => 'XXXXXXXXXXXXXXX', // 商家號
    'netway' => 'XXXXX', // 支付寶'ZFB',微信'WX',QQ錢包'QQ')
    'orderNum' => '20180109023351XXXXX', // 商家產生的唯一訂單號
    'amount'   => 100, //消費金額 (分)
    'goodsName' => 'XXXXXX', //商品名稱
    'payResult' => 'XX', // 支付狀態,00表示成功
    'payDate' => , // 支付時間，格式：yyyyMMddHHmmss
    'sign' => '1C1E6B6DCD8DC9F70565AFXXXXXXXXXX', // 簽名(字母大寫)
]
```

### 掃碼支付訂單查詢

使用商家訂單號查詢單筆訂單狀態。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$merchantPrivateKey = '-----BEGIN RSA PRIVATE KEY-----XXXXXXXXXX-----BEGIN RSA PRIVATE KEY-----' //RSA密鑰
$merchantPayPublicKey = '-----BEGIN PUBLIC KEY-----XXXXXXXXXX-----BEGIN PUBLIC KEY-----' //RSA公鑰

$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 支付通道，支援微信支付、QQ錢包、支付寶
$amount = 1.00; // 消費金額 (元)
$payDate = //支付時間，格式：yyyyMMddHHmmss
```
```
$tradeQuery = new TradeQuery($merchantId, $secretKey, merchantPrivateKey, merchantPayPublicKey);
$result = $tradeQuery->find($tradeNo, $channel, $amount, $payDate);
```
```
Result:
[
    'merNo' => 'XXXXXXXXXXXXXXX', // 商家號
    'msg' => 'XXXX'; // 狀態描述
    'stateCode' => 'XX', // 00表示成功
    'orderNum' =>'20180109023351XXXXX', // 商家產生的唯一訂單號
    'payStateCode' => 'XX', // 支付狀態 00:支付成功 01:支付失敗 03:簽名錯誤 04:其他錯誤 05:未知 06:初始 50:網絡異常 99:未支付
    'sign' => '1C1E6B6DCD8DC9F70565AFXXXXXXXXXX', // 簽名(字母大寫)
]
```

### 掃碼支付訂單支付成功查詢

使用商家訂單號查詢單筆訂單是否支付成功。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$merchantPrivateKey = '-----BEGIN RSA PRIVATE KEY-----XXXXXXXXXX-----BEGIN RSA PRIVATE KEY-----' //RSA密鑰
$merchantPayPublicKey = '-----BEGIN PUBLIC KEY-----XXXXXXXXXX-----BEGIN PUBLIC KEY-----' //RSA公鑰

$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
```
```
$tradeQuery = new TradeQuery($merchantId, $secretKey, merchantPrivateKey, merchantPayPublicKey);
$result = $tradeQuery->isPaid($tradeNo, $channel, $amount, $payDate);
```
```
Result:
bool(true|false)
```   