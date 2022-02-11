<?php

namespace MicrosoftStoreLib;

class StoreServicesClientException extends \Exception
{
    public function __construct(string $message, $inner)
    {
        parent::__construct($message, 0, $inner);
    }
}
