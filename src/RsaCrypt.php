<?php

namespace Jetfuel\Syfpay;

class RsaCrypt
{
    /**
     * Generate rsaEncrypt
     *
     * @param array $payload
     * @param string $publicKey
     * @return string
     */
    public static function rsaEncrypt($payload, $publicKey)
    {
        $baseString = json_encode($payload, 320);

        $publicKey = openssl_pkey_get_public($publicKey);

        $encryptChunk = '';
        $data = '';
        foreach (str_split($baseString, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptChunk, $publicKey);
            $data .= $encryptChunk;
        }

        return urlencode(base64_encode($data));
    }

    public static function rsaDecrypt($payload, $privateKey)
    {
        $privateKey = openssl_get_privatekey($privateKey);

        $data = base64_decode($payload);
        
        $crypto = '';
        foreach (str_split($data, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $privateKey);
            $crypto .= $decryptData;
            
        }
        return $crypto;
    }
}
