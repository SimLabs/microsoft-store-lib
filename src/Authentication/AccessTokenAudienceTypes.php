<?php

namespace MicrosoftStoreLib\Authentication;

/// <summary>
/// Audience URI string values for each of the Access Token types used for Microsoft Store Services auth
/// </summary>
class AccessTokenAudienceTypes
{
    /// <summary>
    /// Service access tokens identify the AAD tenant and your service.
    /// This token is used in the Authorization header of all calls to the Microsoft Store Services.
    /// </summary>
    public static string $Service     = "https://onestore.microsoft.com";

    /// <summary>
    /// Collections access tokens are passed to the client and used to generate UserCollectionsIds.
    /// </summary>
    public static string $Collections = "https://onestore.microsoft.com/b2b/keys/create/collections";

    /// <summary>
    /// Purchase access tokens are passed to your client app and used to generate UserPurchaseIds.
    /// </summary>
    public static string $Purchase    = "https://onestore.microsoft.com/b2b/keys/create/purchase";
}
