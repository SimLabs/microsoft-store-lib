<?php

namespace MicrosoftStoreLib;

use MicrosoftStoreLib\Collections\ConsumeError;

class StoreServicesClientConsumeException extends \Exception
{
    /// <summary>
    /// Initialize a new instance of the StoreServicesClientConsumeException class with the specified error message and
    /// inner exception.
    /// </summary>
    public ConsumeError $ConsumeError;

    public function __construct($inner)
    {
        parent::__construct("consume error");
        $this->ConsumeError = $inner;
    }
}
