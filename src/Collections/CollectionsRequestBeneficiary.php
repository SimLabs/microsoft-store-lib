<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

/// <summary>
/// JSON structure to provide the UserCollectionsId to identify which user we are making the request for.
/// </summary>
class CollectionsRequestBeneficiary
{
    /// <summary>
    /// Must be set to "b2b".
    /// </summary>
    /**
     * @SerializedName("identitytype")
     */
    public string $Identitytype = 'b2b';

    /// <summary>
    /// The User Store ID key that represents the identity of the user for whom you want to report a consumable product as fulfilled.
    /// </summary>
    /**
     * @SerializedName("identityValue")
     */
    public string $UserCollectionsId = '';

    /// <summary>
    /// The requested identifier for the returned response. We recommend that you use the same value as the userId claim in the User Store ID key.
    /// </summary>
    /**
     * @SerializedName("localTicketReference")
     */
    public string $LocalTicketReference = '';
}
