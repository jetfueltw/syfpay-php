<?php

namespace Jetfuel\Syfpay\Constants;

class BaseUrl
{
    const QUERY = 'http://payquery.637pay.com/';
    const URL = [
        Channel::WECHAT => 'http://wx.637pay.com/',
        Channel::ALIPAY => 'http://zfb.637pay.com/',
        Channel::QQ     => 'http://qq.637pay.com/',
        Channel::JD     => 'http://jd.637pay.com/',
        Channel::UNIONPAY => 'http://unionpay.637pay.com/',
    ];
}
