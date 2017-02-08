<?php
/**
 * This file is part of the WrikePhpGuzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Tests\Client;

use GuzzleHttp\ClientInterface;
use Zibios\WrikePhpGuzzle\Client\GuzzleClient;
use Zibios\WrikePhpGuzzle\Transformer\Exception\Api\WrikeTransformer;
use Zibios\WrikePhpGuzzle\Tests\TestCase;
use Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum;
use Zibios\WrikePhpLibrary\Transformer\Exception\Api\RawTransformer;

/**
 * Guzzle Client Test
 */
class GuzzleClientTest extends TestCase
{
    /**
     * Test exception inheritance
     */
    public function test_ExtendProperClasses()
    {
        $apiExceptionTransformerMock = $this->getMock(WrikeTransformer::class);
        $client = new GuzzleClient($apiExceptionTransformerMock, []);

        self::assertInstanceOf(GuzzleClient::class, $client);
        self::assertInstanceOf(ClientInterface::class, $client);
    }

    /**
     * Test exception inheritance
     */
    public function test_getSetBearerToken()
    {
        $testBearerToken = 'test';
        $apiExceptionTransformerMock = $this->getMock(WrikeTransformer::class);
        $client = new GuzzleClient($apiExceptionTransformerMock, []);

        self::assertEquals('', $client->getBearerToken());
        self::assertSame($client, $client->setBearerToken($testBearerToken));
        self::assertEquals($testBearerToken, $client->getBearerToken());
    }

    /**
     * Test exception inheritance
     *
     * @expectedException \InvalidArgumentException
     */
    public function test_setWrongBearerToken()
    {
        $testBearerToken = null;
        $apiExceptionTransformerMock = $this->getMock(WrikeTransformer::class);
        $client = new GuzzleClient($apiExceptionTransformerMock, []);

        self::assertEquals('', $client->getBearerToken());
        self::assertSame($client, $client->setBearerToken($testBearerToken));
    }

    /**
     * @return array
     */
    public function executeRequestForParamsProvider()
    {
        $testUri = '/test/uri';
        $baseOptions['headers'] = [
            'Content-Type' => 'application/json',
        ];
        $bearerToken = 'testBearerToken';
        $baseOptionsWithBearer = $baseOptions;
        $baseOptionsWithBearer['headers']['Authorization'] = sprintf('Bearer %s', $bearerToken);

        return [
            // [bearerToken, requestMethod, path, params, options]
            ['', RequestMethodEnum::GET, $testUri, [], $baseOptions],
            ['', RequestMethodEnum::GET, $testUri, ['test' => 'query'], $baseOptions + ['query' => ['test' => 'query']]],
            ['', RequestMethodEnum::DELETE, $testUri, [], $baseOptions],
            ['', RequestMethodEnum::DELETE, $testUri, ['test' => 'query'], $baseOptions],
            ['', RequestMethodEnum::PUT, $testUri, [], $baseOptions],
            ['', RequestMethodEnum::PUT, $testUri, ['test' => 'query'], $baseOptions + ['json' => ['test' => 'query']]],
            ['', RequestMethodEnum::POST, $testUri, [], $baseOptions],
            ['', RequestMethodEnum::POST, $testUri, ['test' => 'query'], $baseOptions + ['json' => ['test' => 'query']]],

            [$bearerToken, RequestMethodEnum::GET, $testUri, [], $baseOptionsWithBearer],
            [$bearerToken, RequestMethodEnum::GET, $testUri, ['test' => 'query'], $baseOptionsWithBearer + ['query' => ['test' => 'query']]],
        ];
    }

    /**
     * @param string $bearerToken
     * @param string $requestMethod
     * @param string $path
     * @param array $params
     * @param array $options
     *
     * @dataProvider executeRequestForParamsProvider
     */
    public function test_executeRequestForParams($bearerToken, $requestMethod, $path, $params, $options)
    {
        $clientMock = self::getMock(GuzzleClient::class, ['request'], [new RawTransformer()]);
        $clientMock->expects(self::any())
            ->method('request')
            ->with(self::equalTo($requestMethod), self::equalTo($path), self::equalTo($options));
        $clientMock->setBearerToken($bearerToken);

        $clientMock->executeRequestForParams($requestMethod, $path, $params);
    }

    /**
     * @param string $requestMethod
     * @param string $path
     * @param array $params
     * @param array $options
     *
     * @dataProvider executeRequestForParamsProvider
     */
    public function test_executeRequestForParamsWithBearerToken($requestMethod, $path, $params, $options)
    {
        $clientMock = self::getMock(GuzzleClient::class, ['request'], [new RawTransformer()]);
        $clientMock->expects(self::any())
            ->method('request')
            ->with(self::equalTo($requestMethod), self::equalTo($path), self::equalTo($options));

        $clientMock->executeRequestForParams($requestMethod, $path, $params);
    }
}
