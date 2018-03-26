<?php

namespace Jetfuel\Syfpay;

class Signature
{
    /**
     * Generate signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generate(array $payload, $secretKey)
    {
        $baseString = json_encode($payload, 320).$secretKey;

        return self::md5Hash($baseString);
    }

    /**
     * @param array $payload
     * @param string $secretKey
     * @param string $signature
     * @return bool
     */
    public static function validate($data, $secretKey, $signature)
    {
        ksort($data);
        return self::generate($data, $secretKey) === $signature;
    }

    private static function md5Hash($baseString)
    {
        return strtoupper(md5($baseString));
    }
}
