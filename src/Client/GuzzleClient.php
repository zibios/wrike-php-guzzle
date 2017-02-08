<?php
/**
 * This file is part of the WrikePhpGuzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Client;

use GuzzleHttp\Client as BaseClient;
use Psr\Http\Message\ResponseInterface;
use Zibios\WrikePhpLibrary\Client\ClientInterface;
use Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum;
use Zibios\WrikePhpLibrary\Exception\Api\ApiException;
use Zibios\WrikePhpLibrary\Transformer\ApiExceptionTransformerInterface;

/**
 * Guzzle Client
 */
class GuzzleClient extends BaseClient implements ClientInterface
{
    /**
     * @var string
     */
    protected $bearerToken = '';

    /**
     * @var ApiExceptionTransformerInterface
     */
    protected $apiExceptionTransformer;

    /**
     * Client constructor.
     *
     * @param ApiExceptionTransformerInterface $apiExceptionTransformer
     * @param array $config
     */
    public function __construct(ApiExceptionTransformerInterface $apiExceptionTransformer, array $config = [])
    {
        $this->apiExceptionTransformer = $apiExceptionTransformer;
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * @param string $bearerToken
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBearerToken($bearerToken)
    {
        if (is_string($bearerToken) === false) {
            throw new \InvalidArgumentException('Bearer Token should be a string!');
        }
        $this->bearerToken = $bearerToken;

        return $this;
    }

    /**
     * @param string $requestMethod
     * @param string $path
     * @param array $params
     *
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     * @throws \Exception|ApiException
     */
    public function executeRequestForParams($requestMethod, $path, array $params)
    {
        $options = $this->calculateOptionsForParams($requestMethod, $params);

        return $this->request($requestMethod, $path, $options);
    }

    /**
     * @param string $requestMethod
     * @param array $params
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function calculateOptionsForParams($requestMethod, array $params)
    {
        $requestMethod = strtoupper($requestMethod);
        $options = [];

        switch ($requestMethod) {
            case RequestMethodEnum::GET:
                if (count($params) > 0) {
                    $options['query'] = $params;
                }
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
        $options['headers'] = [
            'Content-Type' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $this->bearerToken),
        ];

        return $options;
    }

    /**
     * @param \Exception $exception
     *
     * @return \Exception|ApiException
     */
    public function transformApiException(\Exception $exception)
    {
        return $this->apiExceptionTransformer->transform($exception);
    }
}
