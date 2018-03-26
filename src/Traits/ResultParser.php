<?php

namespace Jetfuel\Syfpay\Traits;

use Jetfuel\Syfpay\Signature;

trait ResultParser
{
    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @param  $secretKey
     * @return array
     */
    public function parseResponse($response, $secretKey)
    {
        $result = json_decode($response, true);
        if ($result['stateCode'] == '00')
        {
            $signature = $result['sign'];
            unset($result['sign']);
            if (Signature::validate($result, $secretKey, $signature)) //sign is correct
            {
                return json_decode($response, true);
            }
            else 
            {
                $signError = '{"stateCode" : "03", "msg" : "签名错误"}';
                return json_decode($signError, true);
            }
        }
        return json_decode($response, true);;
    }
}
