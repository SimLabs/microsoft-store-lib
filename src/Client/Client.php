<?php

namespace MicrosoftStoreLib\Client;

use GuzzleHttp\Promise\PromiseInterface;
use MicrosoftStoreLib\ArgumentException;
use MicrosoftStoreLib\Authentication\IAccessTokenProvider;
use MicrosoftStoreLib\Collections\CollectionsConsumeErrorResponse;
use MicrosoftStoreLib\Collections\CollectionsConsumeRequest;
use MicrosoftStoreLib\Collections\CollectionsConsumeResponse;
use MicrosoftStoreLib\Collections\CollectionsQueryRequest;
use MicrosoftStoreLib\Collections\CollectionsQueryResponse;
use MicrosoftStoreLib\IHttpClientFactory;
use MicrosoftStoreLib\Serializer;
use MicrosoftStoreLib\StoreServicesClientConsumeException;
use MicrosoftStoreLib\StoreServicesClientException;
use MicrosoftStoreLib\StoreServicesHttpResponseException;

/// <summary>
/// Client to manage the access tokens, authentication, calls, and requests to the Microsoft Store Services.
/// </summary>
class Client //: IClient
{
    /// <summary>
    /// Can be overridden with an HttpClientFactory.CreateClient() if used by your service.
    /// Ex: StoreServicesClient.CreateHttpClientFunc = httpClientFactory.CreateClient;
    /// </summary>
    public IHttpClientFactory $CreateHttpClientFunc;

    /// <summary>
    /// Identification string of your service for logging purposes on the calls to the Microsoft
    /// Store Services.
    /// </summary>
    public string $ServiceIdentity;

    /// <summary>
    /// Manages the access tokens required for authenticating our calls to the Microsoft Store Services.
    /// </summary>
    private IAccessTokenProvider $_accessTokenProvider;

    /// <summary>
    /// Used for disposing of the item.
    /// </summary>
    private bool $_isDisposed;

    /// <summary>
    /// Creates a client that will manage the auth and calls to the Microsoft Store Services.
    /// </summary>
    /// <param name="serviceIdentity">Identification string of your service for logging purposes on the calls to
    /// the Microsoft Store Services.</param>
    /// <param name="accessTokenProvider">IAccessTokenProvider created to manages the access tokens required for
    /// authenticating our calls to the Microsoft Store Services.</param>
    public function __construct(IHttpClientFactory $clientFactory, IAccessTokenProvider $accessTokenProvider)
    {
        $this->CreateHttpClientFunc = $clientFactory;
        $this->_accessTokenProvider = $accessTokenProvider;
        $this->ServiceIdentity = "UnspecifiedService-Microsoft.StoreServices";
        $this->_isDisposed = false;
    }

    /// <summary>
    /// Creates and executes the HTTP request to the target Microsoft Store Service then parses the JSON
    /// response based on the specified response type.
    /// </summary>
    /// <typeparam name="T">JSON response type to expect</typeparam>
    /// <param name="uri">URI of the target Microsoft Store Service</param>
    /// <param name="requestBodyString">JSON request body</param>
    /// <param name="additionalHeaders">Any additional headers that are needed more than the default headers</param>
    /// <returns><typeparamref name="T"/> object from the JSON response of the HTTP request</returns>
    private function IssueRequestAsync(string $uri, string $requestBodyString, $additionalHeaders = null)
    {
        $clientFactory = $this->CreateHttpClientFunc;
        $client = $clientFactory->Create();
        //  Add the Authorization header for AAD / StoreID keys
        return $this->_accessTokenProvider->GetServiceAccessTokenAsync()->then(
            function ($serviceToken) use ($additionalHeaders, $client, $uri, $requestBodyString) {
                $headers = [
                    "Authorization" => "Bearer {$serviceToken->Token}",
                    "User-Agent" => $this->ServiceIdentity,
                    "Content-Type" => 'application/json'
                ];

                if ($additionalHeaders !== null) {
                    //  Add the rest of the headers the caller wants
                    foreach ($additionalHeaders as $key => $value) {
                        $headers[$key] = $value;
                    }
                }

                //  issue the request to the service
                return $client->requestAsync('POST', $uri, ['headers' => $headers, 'body' => $requestBodyString])->then(
                    function ($httpResponse) use ($uri, $requestBodyString) {
                        $responseStatus = $httpResponse->getStatusCode();
                        if (!($responseStatus >= 200 && $responseStatus < 300)) {
                            throw new StoreServicesHttpResponseException("HTTP request {$uri} {$requestBodyString} failed with status code {$responseStatus}.", $httpResponse);
                        }

                        $responseBody = $httpResponse->getBody();
                        return $responseBody;

                        //  De-serialize the JSON response and pass it back.  All responses from the
                        //  Microsoft Store Services use UTC time so we make sure to specify that.
                        // return JsonConvert.DeserializeObject<T>(responseBody, new JsonSerializerSettings
                        // {
                        //     DateTimeZoneHandling = DateTimeZoneHandling.Utc
                        // });
                    },
                    function ($httpReqEx) use ($uri, $requestBodyString) {
                        throw new StoreServicesClientException("HTTP request {$uri} {$requestBodyString} failed: {$httpReqEx->getMessage()}", $httpReqEx);
                    }
                );
            }
        );
    }

    /// <summary>
    /// Provides a Service Access Token from the IAccessTokenProvider.
    /// </summary>
    /// <returns></returns>
    public function GetServiceAccessTokenAsync(): PromiseInterface
    {
        return $this->_accessTokenProvider->GetServiceAccessTokenAsync();
    }

    /// <summary>
    /// Provides a Service Access Token from the IAccessTokenProvider.
    /// </summary>
    /// <returns></returns>
    public function GetCollectionsAccessTokenAsync(): PromiseInterface
    {
        return $this->_accessTokenProvider->GetCollectionsAccessTokenAsync();
    }

    /// <summary>
    /// Provides a Service Access Token from the IAccessTokenProvider.
    /// </summary>
    /// <returns></returns>
    public function GetPurchaseAccessTokenAsync(): PromiseInterface
    {
        return $this->_accessTokenProvider->GetPurchaseAccessTokenAsync();
    }

    public function CollectionsQueryAsync(CollectionsQueryRequest $request) /*: Task<CollectionsQueryResponse>*/
    {
        //  Validate that we have a UserCollectionsId
        if (
            $request->Beneficiaries === null ||
            count($request->Beneficiaries) != 1 ||
            !($request->Beneficiaries[0]->UserCollectionsId)
        ) {
            throw new ArgumentException("request.Beneficiaries must be provided");
        }

        //  Now pass these values to get the correct Delegated Auth and Signature headers for
        //  the request
        //  Post the request and wait for the response
        $requestBody = Serializer::Get()->serialize($request, 'json');

        return $this->IssueRequestAsync(
            "https://collections.mp.microsoft.com/v8.0/collections/b2bLicensePreview",
            $requestBody
        )->then(function ($responseBody) {
            return Serializer::Get()->deserialize($responseBody, CollectionsQueryResponse::class, 'json');
        });
    }

    /// <summary>
    /// Preform a consume transaction from the user's balance of the product based on the request parameters.
    /// </summary>
    /// <param name="request"></param>
    /// <returns></returns>
    public function CollectionsConsumeAsync(CollectionsConsumeRequest $request): PromiseInterface/*<CollectionsConsumeResponse>*/
    {
        //  Validate that we have a productID, quantity, trackingId and a UserCollectionsId
        {
            if (!($request->ProductId)) {
                throw new ArgumentException("request.ProductId must be provided");
            }
            if ($request->RemoveQuantity <= 0) {
                throw new ArgumentException("request.RemoveQuantity must be greater than 0");
            }
            if (!($request->TrackingId)) {
                throw new ArgumentException("request.TrackingId must be provided");
            }
            if ($request->Beneficiary === null) {
                throw new ArgumentException("request.Beneficiary must be provided");
            }
            if (!($request->Beneficiary->UserCollectionsId)) {
                throw new ArgumentException("request.Beneficiary.UserCollectionsId must be provided", "request.Beneficiary.UserCollectionsId");
            }
        }


        //  Post the request and wait for the response
        return $this->IssueRequestAsync(
            "https://collections.mp.microsoft.com/v8.0/collections/consume",
            Serializer::Get()->serialize($request, 'json'),
            null
        )->then(
            function ($responseBody) {
                return Serializer::Get()->deserialize($responseBody, CollectionsConsumeResponse::class, 'json');
            },
            function ($ex) {
                //  Consume failures have a specific body format that will give us more information so we need to
                //  deserialize the JSON into a ConsumeError object
                $responseError = null;
                try {
                    $responseBody = $ex->HttpResponse->getBody();
                    $responseError = Serializer::Get()->deserialize($responseBody, CollectionsConsumeErrorResponse::class, 'json');
                } catch (\Exception $e) {
                    throw new StoreServicesClientException("Unable to parse ConsumeErrorResponse", $e);
                }

                throw new StoreServicesClientConsumeException($responseError->InnerError);
            }
        );
    }
}
