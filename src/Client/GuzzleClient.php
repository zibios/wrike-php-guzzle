<?php

/*
 * This file is part of the zibios/wrike-php-guzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Client;

use GuzzleHttp\Client as BaseClient;
use Psr\Http\Message\ResponseInterface;
use Zibios\WrikePhpLibrary\Api;
use Zibios\WrikePhpLibrary\Client\ClientInterface;
use Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum;
use Zibios\WrikePhpLibrary\Enum\Api\ResponseFormatEnum;
use Zibios\WrikePhpLibrary\Validator\AccessTokenValidator;

/**
 * Guzzle Client for Wrike library.
 */
class GuzzleClient extends BaseClient implements ClientInterface
{
    /**
     * @return string
     */
    public function getResponseFormat()
    {
        return ResponseFormatEnum::PSR_RESPONSE;
    }

    /**
     * Request method.
     *
     * Generic format for HTTP client request method.
     *
     * @param string $requestMethod GET/POST/PUT/DELETE
     * @param string $path          full path to REST resource without domain, ex. 'accounts/XXXXXXXX/contacts'
     * @param array  $params        optional params for GET/POST request
     * @param string $accessToken   Access Token for Wrike access
     *
     * @see \Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum
     * @see \Zibios\WrikePhpLibrary\Enum\Api\RequestPathFormatEnum
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     *
     * @return string|ResponseInterface
     */
    public function executeRequestForParams($requestMethod, $path, array $params, $accessToken)
    {
        RequestMethodEnum::assertIsValidValue($requestMethod);
        $options = $this->calculateOptionsForParams($requestMethod, $params, $accessToken);
        if (RequestMethodEnum::UPLOAD === $requestMethod) {
            $requestMethod = RequestMethodEnum::POST;
        }

        return $this->request($requestMethod, $path, $options);
    }

    /**
     * Main method for calculating request params.
     *
     * @param string $requestMethod
     * @param array  $params
     * @param $accessToken
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function calculateOptionsForParams($requestMethod, array $params, $accessToken): array
    {
        $options = $this->prepareBaseOptions($accessToken);
        if (0 === \count($params)) {
            return $options;
        }

        switch ($requestMethod) {
            case RequestMethodEnum::GET:
            case RequestMethodEnum::DELETE:
                $options['query'] = $params;
                break;
            case RequestMethodEnum::PUT:
            case RequestMethodEnum::POST:
                $options['form_params'] = $params;
                break;
            case RequestMethodEnum::UPLOAD:
                if (array_key_exists('resource', $params) && array_key_exists('name', $params)) {
                    $options['headers']['X-File-Name'] = $params['name'];
                    $options['multipart'] = [
                        [
                            'contents' => $params['resource'],
                            'name' => $params['name'],
                        ],
                    ];
                }
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Request method "%s" not allowed!', $requestMethod));
        }

        return $options;
    }

    /**
     * @param $accessToken
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function prepareBaseOptions($accessToken): array
    {
        AccessTokenValidator::assertIsValid($accessToken);
        $options = [];
        $options['headers']['Authorization'] = sprintf('Bearer %s', $accessToken);
        $options['base_uri'] = Api::BASE_URI;

        return $options;
    }
}
