<?php

namespace MicrosoftStoreLib\Authentication;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use MicrosoftStoreLib\IHttpClientFactory;
use MicrosoftStoreLib\Serializer;
use MicrosoftStoreLib\ArgumentException;
use MicrosoftStoreLib\StoreServicesHttpResponseException;

/// <summary>
/// An IAccessTokenProvider that generates access tokens required for Microsoft Store
/// Services authentication.
/// </summary>
class AccessTokenProvider implements IAccessTokenProvider
{
    /// <summary>
    /// Can be overridden with an HttpClientFactory.CreateClient() if used by your service.
    /// Ex: AccessTokenProvider.CreateHttpClientFunc = httpClientFactory.CreateClient;
    /// </summary>
    public IHttpClientFactory $CreateHttpClientFunc;

    /// <summary>
    /// Registered AAD Tenant Id for your service.
    /// </summary>
    protected string $_tenantId;

    /// <summary>
    /// Registered AAD Client Id for your service.
    /// </summary>
    protected string $_clientId;

    /// <summary>
    /// Registered AAD Client secret for your service.
    /// </summary>
    protected string $_clientSecret;

    /// <summary>
    /// Generates an access token provider based on your AAD credentials passed in that will generate
    /// access tokens required to authenticate with the Microsoft Store Services.
    /// </summary>
    /// <param name="tenantId">Registered AAD Tenant Id for your service</param>
    /// <param name="clientId">Registered AAD Client Id for your service</param>
    /// <param name="clientSecret">Registered AAD Client secret for your service</param>
    public function __construct(IHttpClientFactory $clientFactory, string $tenantId, string $clientId, string $clientSecret)
    {
        $this->CreateHttpClientFunc = $clientFactory;

        if (!($tenantId)) {
            throw new ArgumentException("tenantId required");
        }
        if (!($clientId)) {
            throw new ArgumentException("clientId required");
        }
        if (!($clientSecret)) {
            throw new ArgumentException("clientSecret required");
        }

        $this->_tenantId = $tenantId;
        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;
    }

    public function GetServiceAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->CreateAccessTokenAsync(AccessTokenAudienceTypes::$Service);
    }

    public function GetCollectionsAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->CreateAccessTokenAsync(AccessTokenAudienceTypes::$Collections);
    }

    public function GetPurchaseAccessTokenAsync(): PromiseInterface/*<AccessToken>*/
    {
        return $this->CreateAccessTokenAsync(AccessTokenAudienceTypes::$Purchase);
    }

    /// <summary>
    /// Generates an access token based on the URI audience value passed in.
    /// provided.
    /// </summary>
    /// <param name="audience">Audience URI defining the token (see AccessTokenAudienceTypes)</param>
    /// <returns>Access token, otherwise Exception will be thrown</returns>
    protected function CreateAccessTokenAsync(string $audience): PromiseInterface/*<AccessToken>*/
    {
        //  Validate we have the needed values
        if (!($audience)) {
            throw new ArgumentException("audience required");
        }

        //  URL encode the Secret key to ensure it gets properly transmitted if containing
        //  characters such as '%'.  We just encode the secret so the rest of the body is
        //  easily read in debugging tools such as Fiddler.
        $encodedSecret = ($this->_clientSecret);

        //  Build the HTTP request information to generate the access token.  We are using
        //  Azure AD v2.0 to generate the tokens. See the following:
        //  https://docs.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-client-creds-grant-flow

        $requestUri = "https://login.microsoftonline.com/{$this->_tenantId}/oauth2/token";

        $clientFactory = $this->CreateHttpClientFunc;
        $httpClient = $clientFactory->Create();

        $httpResponsePromise = $httpClient->requestAsync('POST', $requestUri, [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->_clientId,
                'client_secret' => $encodedSecret,
                'resource' => "{$audience}"
            ]
        ]);
        // Post the request and wait for the response
        return $httpResponsePromise->then(function (Response $httpResponse) use ($audience) {
            $responseStatus = $httpResponse->getStatusCode();

            if ($responseStatus >= 200 && $responseStatus < 300) {
                $responseBody = $httpResponse->getBody();
                $token = Serializer::Get()->deserialize($responseBody, AccessToken::class, 'json');
                if (!($token->Audience)) {
                    //  The Azure v2.0 AAD URI doesn't pass back the audience in the request body
                    //  so we copy it from the audience that we put in the request
                    $token->Audience = $audience;
                }
                return $token;
            } else {
                throw new StoreServicesHttpResponseException("Unable to acquire access token for {$audience} : {$httpResponse->getReasonPhrase()}", $httpResponse);
            }
        });
    }
}
