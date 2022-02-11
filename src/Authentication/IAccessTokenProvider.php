<?php

namespace MicrosoftStoreLib\Authentication;

use GuzzleHttp\Promise\PromiseInterface;

interface IAccessTokenProvider
{
    /// <summary>
    /// Provides a Service access token for your service that will have an audience
    /// of https://onestore.microsoft.com
    /// </summary>
    /// <returns></returns>
    public function GetServiceAccessTokenAsync(): PromiseInterface/*<AccessToken>*/;

    /// <summary>
    /// Provides a Collections access token for your service that will have an audience
    /// of https://onestore.microsoft.com/b2b/keys/create/collections
    /// </summary>
    /// <returns></returns>
    public function GetCollectionsAccessTokenAsync(): PromiseInterface/*<AccessToken>*/;

    /// <summary>
    /// Provides a Purchase access token for your service that will have an audience
    /// of https://onestore.microsoft.com/b2b/keys/create/purchase
    /// </summary>
    /// <returns></returns>
    public function GetPurchaseAccessTokenAsync(): PromiseInterface/*<AccessToken>*/;
}
