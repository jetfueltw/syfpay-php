<?php

namespace Jetfuel\Syfpay\HttpClient;

class CurlHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var resource a cURL handle on success, false on errors.
     */
    private $client;

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/').'/';
        $this->client = curl_init();
    }

    /**
     * POST request.
     *
     * @param string $uri
     * @param string $data
     * @return string
     */
    public function post($uri, $data)
    {
        curl_setopt_array($this->client, [
            CURLOPT_URL            => $this->baseUrl.$uri,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_AUTOREFERER    => 1,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = curl_exec($this->client);

        return $result;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
