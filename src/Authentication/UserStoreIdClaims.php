<?php

namespace MicrosoftStoreLib\Authentication;

use Symfony\Component\Serializer\Annotation\SerializedName;

class UserStoreIdClaims
{
    /// <summary>
    /// Azure Active Directory App Client ID used for the Access Token that generated this
    /// UserStoreId
    /// </summary>
    /**
     * @SerializedName("http://schemas.microsoft.com/marketplace/2015/08/claims/key/clientId")
     */
    public string $ClientId;

    /// <summary>
    /// The PublisherUserId string that was used when generating the UserStoreId on the
    /// Client.  NOTE: This is controlled by the client and does not represent an actual
    /// identity value of the user account this UserStoreId represents.
    /// </summary>
    /**
     * @SerializedName("http://schemas.microsoft.com/marketplace/2015/08/claims/key/userId")
     */
    public string $UserId;

    /// <summary>
    /// Payload used by the Microsoft Store Services as part of authentication
    /// </summary>
    /**
     * @SerializedName("http://schemas.microsoft.com/marketplace/2015/08/claims/key/payload")
     */
    public string $Payload;

    /// <summary>
    /// The URI that can be used to refresh this UserStoreId once it has expired without
    /// generating a new one from the client.
    /// </summary>
    /**
     * @SerializedName("http://schemas.microsoft.com/marketplace/2015/08/claims/key/refreshUri")
     */
    public string $RefreshUri;

    /// <summary>
    /// Service or identity of who created and signed the JWT representing the UserStoreId
    /// </summary>
    /**
     * @SerializedName("iss")
     */
    public string $Issuer;

    /// <summary>
    /// Represents if this is a UserCollectionsId or UserPurchaseId.  See UserStoreIdAudiences.
    /// </summary>
    /**
     * @SerializedName("aud")
     */
    public string $Audience;

    /// <summary>
    /// Seconds from the Unix Epoc that represents the UTC datetime the UserStoreId was generated
    /// </summary>
    /**
     * @SerializedName("iat")
     */
    public int $EpochIssuedOn;

    /// <summary>
    /// Seconds from the Unix Epoc that represents the UTC datetime the UserStoreId will expire
    /// </summary>
    /**
     * @SerializedName("exp")
     */
    public int $EpochExpiresOn;

    /// <summary>
    /// Seconds from the Unix Epoc that represents the UTC datetime the UserStoreId starts being valid and can be used.
    /// </summary>
    /**
     * @SerializedName("nbf")
     */
    public int $EpochValidAfter;

    /// <summary>
    /// The UTC date and time when the UserStoreId becomes valid and can be used
    /// </summary>
    public function getValidAfter()
    {
        return (new \DateTime())->setTimestamp($this->EpochValidAfter);
    }

    /// <summary>
    /// The UTC date and time when the UserStoreId expires
    /// </summary>
    public function getExpiresOn()
    {
        return (new \DateTime())->setTimestamp($this->EpochExpiresOn);
    }

    /// <summary>
    /// The UTC date and time when the UserStoreId was created
    /// </summary>
    public function getIssuedOn()
    {
        return (new \DateTime())->setTimestamp($this->EpochIssuedOn);
    }
}
