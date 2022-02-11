<?php

namespace MicrosoftStoreLib;

class DefaultGazzleHttpFactory implements IHttpClientFactory
{
    public function Create(): \GuzzleHttp\Client
    {
        $options = [
            'http_errors' => false,
            'verify' => true,
        ];
        return new \GuzzleHttp\Client($options);
    }
}
