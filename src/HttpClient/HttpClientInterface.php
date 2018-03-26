<?php

namespace Jetfuel\Syfpay\HttpClient;

interface HttpClientInterface
{
    /**
     * HttpClientInterface constructor.
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl);

    /**
     * POST request.
     *
     * @param string $uri
     * @param string $data
     * @return string
     */
    public function post($uri, $data);
}
