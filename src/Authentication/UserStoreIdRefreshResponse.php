<?php

namespace MicrosoftStoreLib\Authentication;

use Symfony\Component\Serializer\Annotation\SerializedName;

class UserStoreIdRefreshResponse
{
    /**
     * @SerializedName("key")
     */
    public string $Key;
}
