<?php

namespace MicrosoftStoreLib;

interface IHttpClientFactory
{
    public function Create(): \GuzzleHttp\Client;
}
