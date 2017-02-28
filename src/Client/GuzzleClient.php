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
use Zibios\WrikePhpLibrary\Exception\Api\ApiException;
use Zibios\WrikePhpLibrary\Traits\AssertIsValidBearerToken;

/**
 * Guzzle Client.
 */
class GuzzleClient extends BaseClient implements ClientInterface
{
    use AssertIsValidBearerToken;

    /**
     * @var string
     */
    protected $bearerToken = '';

    /**
     * @param string $bearerToken
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setBearerToken($bearerToken)
    {
        $this->assertIsValidBearerToken($bearerToken);
        $this->bearerToken = $bearerToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponseFormat()
    {
        return ResponseFormatEnum::PSR_RESPONSE;
    }

    /**
     * @param string $requestMethod
     * @param string $path
     * @param array  $params
     *
     * @throws \InvalidArgumentException
     * @throws \Exception|ApiException
     *
     * @return ResponseInterface
     */
    public function executeRequestForParams($requestMethod, $path, array $params)
    {
        $options = $this->calculateOptionsForParams($requestMethod, $params);

        return $this->request($requestMethod, $path, $options);
    }

    /**
     * @param string $requestMethod
     * @param array  $params
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function calculateOptionsForParams($requestMethod, array $params)
    {
        $options = $this->prepareBaseOptions();
        if (count($params) === 0) {
            return $options;
        }

        switch ($requestMethod) {
            case RequestMethodEnum::GET:
                $options['query'] = $params;
                break;
            case RequestMethodEnum::PUT:
            case RequestMethodEnum::POST:
                if (count($params) > 0) {
                    $options['json'] = $params;
                }
                break;
            case RequestMethodEnum::DELETE:
                break;
            default:
                throw new \InvalidArgumentException();
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function prepareBaseOptions()
    {
        $options = [];
        $options['headers']['Content-Type'] = 'application/json';
        if ($this->bearerToken !== '') {
            $options['headers']['Authorization'] = sprintf('Bearer %s', $this->bearerToken);
        }
        $options['base_uri'] = Api::BASE_URI;

        return $options;
    }
}
