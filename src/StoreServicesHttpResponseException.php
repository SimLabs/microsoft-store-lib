<?php

namespace MicrosoftStoreLib;

class StoreServicesHttpResponseException extends \Exception
{
    public $HttpResponse;
    public function __construct($message, $httpResponse)
    {
        parent::__construct($message);
        $this->HttpResponse = $httpResponse;
    }
}
