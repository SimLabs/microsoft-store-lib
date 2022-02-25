<?php

namespace MicrosoftStoreLib\Authentication;

use GuzzleHttp\Promise\PromiseInterface;
use MicrosoftStoreLib\Cache\IMemoryCache;
use MicrosoftStoreLib\IHttpClientFactory;
use MicrosoftStoreLib\ArgumentException;

class CachedAccessTokenProvider extends AccessTokenProvider
{
    /// <summary>
    /// Cache used to store and retrieve access tokens
    /// </summary>
    private IMemoryCache $_serverCache;

    /// <summary>
    /// Generates an access token provider that will manage a cache of the access tokens based
    /// on your AAD credentials provided.
    /// <param name="serverCache">IMemoryCache to be used to cache and retrieve the tokens</param>
    /// <param name="tenantId">Registered AAD Tenant Id for your service</param>
    /// <param name="clientId">Registered AAD Client Id for your service</param>
    /// <param name="clientSecret">Registered AAD Client secret for your service</param>
    public function __construct(
        IHttpClientFactory $clientFactory,
        IMemoryCache $serverCache,
        $tenantId,
        $clientId,
        $clientSecret
    ) {
        parent::__construct($clientFactory, $tenantId, $clientId, $clientSecret);

        if ($serverCache === null) {
            throw new ArgumentException("serverCache required");
        }
        $this->_serverCache = $serverCache;
    }

    /// <summary>
    /// Retrieves a valid cached access token based on the audience provided. If not cached,
    /// a new token is created and cached.
    /// </summary>
    /// <param name="audience"></param>
    /// <returns></returns>
    protected function GetTokenAsync(string $audience): PromiseInterface/*<AccessToken>*/
    {
        $token = $this->_serverCache->get($audience);
        //  If we are unable to acquire a token, it is expired, or expiring
        //  in less than 5 minutes we create a new one and cache it
        if (
            is_null($token) ||
            ($token->expiresOn->sub(new \DateInterval("PT5M"))) <= new \DateTime()
        ) {
            return $this->CreateAccessTokenAsync($audience)->then(function ($token) use ($audience) {
                $this->CacheAccessToken($audience, $token);
                return $token;
            });
        }

        return new \GuzzleHttp\Promise\FulfilledPromise($token);
    }

    /// <summary>
    /// Adds this token to the cache so that future calls for the same audience do not
    /// require generating a new one.
    /// </summary>
    /// <param name="audience"></param>
    /// <param name="token"></param>
    private function CacheAccessToken(string $audience, AccessToken $token)
    {
        $this->_serverCache->set($audience, $token, $token->getExpiresIn());
    }

    public function GetServiceAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->GetTokenAsync(AccessTokenAudienceTypes::$Service);
    }

    public function GetCollectionsAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->GetTokenAsync(AccessTokenAudienceTypes::$Collections);
    }

    public function GetPurchaseAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->GetTokenAsync(AccessTokenAudienceTypes::$Purchase);
    }

    public function GetSubmissionsTokenAsync(): PromiseInterface
    {
        return $this->GetTokenAsync(AccessTokenAudienceTypes::$Submissions);
    }
}
