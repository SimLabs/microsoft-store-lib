<?php

namespace MicrosoftStoreLib\Authentication;

use Symfony\Component\Serializer\Annotation\SerializedName;

class UserStoreIdRefreshRequest
{
    /**
     * @SerializedName("serviceTicket")
     */
    public string $ServiceToken;

    /**
     * @SerializedName("key")
     */
    public string $UserStoreId;
}
