<?php

namespace MicrosoftStoreLib\Submissions;

use GuzzleHttp\Psr7\Response;
use MicrosoftStoreLib\Authentication\IAccessTokenProvider;
use MicrosoftStoreLib\IHttpClientFactory;
use MicrosoftStoreLib\Serializer;
use MicrosoftStoreLib\StoreServicesHttpResponseException;

class SubmissionsClient
{
    private $_tokenProvider;
    private IHttpClientFactory   $_clientFactory;
    private \GuzzleHttp\Client   $_httpClient;

    public function __construct(IHttpClientFactory $clientFactory, $tokenProvider = null)
    {
        $this->_tokenProvider = $tokenProvider;
        $this->_clientFactory = $clientFactory;
        $this->_httpClient = $clientFactory->Create();
    }

    private function getAccessToken()
    {
        return $this->_tokenProvider->GetSubmissionsTokenAsync()->then(
            function ($token) {
                return $token->Token;
            }
        );
    }

    public function issueRequest(string $method, $uri, $jsonBody = null, array $addHeaders = [])
    {
        return $this->getAccessToken()->then(function ($token)
        use ($method, $uri, $jsonBody, $addHeaders) {
            $authHeader = "Bearer $token";
            // if jsonBody is a string add body and Content-Type
            // otherwise add json
            $options = array_merge([
                'headers' => array_merge(
                    ['Authorization' => $authHeader],
                    is_string($jsonBody) ? ['Content-Type' => 'application/json'] : [],
                    $addHeaders,
                ),
            ], is_null($jsonBody) ? [] : (is_string($jsonBody) ? ['body' => $jsonBody] : ['json' => $jsonBody]));

            return $this->_httpClient->requestAsync($method, $uri, $options);
        });
    }

    private function responseCallback(Response $resp, string $callDescription, ?string $responseClass = null, callable $retryFunction)
    {
        //print($callDescription . "\n");
        $status = $resp->getStatusCode();
        $body = (string) $resp->getBody();
        if ($status >= 200 && $status < 300) {
            if (!is_null($responseClass))
                return Serializer::Get()->deserialize($body, $responseClass, 'json');
            else
                return true;
        } else if ($status === 429) {
            if (!is_null($retryFunction)) {
                try {
                    $message = json_decode($body, true)['message'];
                    if (preg_match('/Try again in (\d+) seconds/', $message, $matches, PREG_OFFSET_CAPTURE)) {
                        $timeout = intval($matches[1][0]) + 1;
                        print("Rate limit is exceeded. Retrying in $timeout seconds.");
                        sleep($timeout);
                        return $retryFunction();
                    }
                } catch (\Exception $e) {
                    print("failed to retry request: " . $e->getMessage() . "\n");
                    throw new StoreServicesHttpResponseException("{$callDescription} failed [{$status}] {$body}", $resp);
                }
            } else {
                throw new StoreServicesHttpResponseException("{$callDescription} failed [{$status}] {$body}", $resp);
            }
        } else {
            throw new StoreServicesHttpResponseException("{$callDescription} failed [{$status}] {$body}", $resp);
        }
    }

    private function args2str($args)
    {
        return '(' . join(', ', array_map(function ($a) {
            return (string) $a;
        }, $args)) . ')';
    }

    private function getAbsoluteUri(string $relative)
    {
        return 'https://manage.devcenter.microsoft.com/v1.0/my/' . $relative;
    }

    public function getApplicationAddOnsByLink(string $link)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('GET', $this->getAbsoluteUri($link))
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AppAddOnsResponse::class, $thisFunction);
            });
    }

    public function getApplicationAddOns(string $applicationId, ?int $top = null, ?int $skip = null)
    {
        $params = [];
        if (!is_null($top))
            $params['top'] = $top;
        if (!is_null($skip))
            $params['skip'] = $skip;

        $link = "https://manage.devcenter.microsoft.com/v1.0/my/applications/{$applicationId}/listinappproducts";
        if (count($params))
            $link = $link . '?' . http_build_query($params);

        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('GET', $link)
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AppAddOnsResponse::class, $thisFunction);
            });
    }

    public function createAddOn(string $appId, string $productId, string $productType)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest(
            'POST',
            'https://manage.devcenter.microsoft.com/v1.0/my/inappproducts',
            [
                'applicationIds' => [$appId],
                'productId' => $productId,
                'productType' => $productType,
            ]
        )
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AddOnResource::class, $thisFunction);
            });
    }

    public function getAddOn(string $productId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('GET', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AddOnResource::class, $thisFunction);
            });
    }

    public function deleteAddOn($productId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('DELETE', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, null, $thisFunction);
            });
    }

    public function createAddOnSubmission(string $productId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('POST', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}/submissions")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AddOnSubmissionResource::class, $thisFunction);
            });
    }

    public function getAddOnSubmission(string $productId, string $submissionId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('GET', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}/submissions/{$submissionId}")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, AddOnSubmissionResource::class, $thisFunction);
            });
    }

    public function updateAddOnSubmission(string $productId, string $submissionId, UpdateAddOnSubmissionResource $data)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest(
            'PUT',
            "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}/submissions/{$submissionId}",
            (string) $data
        )->then(function ($resp) use ($callDescription, $thisFunction) {
            return $this->responseCallback($resp, $callDescription, AddOnSubmissionResource::class, $thisFunction);
        });
    }

    public function deleteAddOnSubmission(string $productId, string $submissionId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('DELETE', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}/submissions/{$submissionId}")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, null, $thisFunction);
            });
    }

    public function commitAddOnSubmission(string $productId, string $submissionId)
    {
        $args = func_get_args();
        $callDescription = __FUNCTION__ . $this->args2str($args);
        $funcName = __FUNCTION__;
        $thisFunction = function () use ($args, $funcName) {
            return call_user_func_array([__CLASS__, $funcName], $args);
        };

        return $this->issueRequest('POST', "https://manage.devcenter.microsoft.com/v1.0/my/inappproducts/{$productId}/submissions/{$submissionId}/commit")
            ->then(function ($resp) use ($callDescription, $thisFunction) {
                return $this->responseCallback($resp, $callDescription, CommitResponse::class, $thisFunction);
            });
    }
}
