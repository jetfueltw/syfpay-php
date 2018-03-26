<?php

namespace Jetfuel\Syfpay\Constants;

class BaseUrl
{
    const QUERY = 'http://payquery.qyfpay.com:90/';
    const URL = [
        Channel::WECHAT => 'http://wx.qyfpay.com:90/',
        Channel::ALIPAY => 'http://zfb.qyfpay.com:90/',
        Channel::QQ     => 'http://qq.qyfpay.com:90/',
    ];
}
