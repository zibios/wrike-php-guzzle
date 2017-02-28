<?php

/*
 * This file is part of the zibios/wrike-php-guzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Tests\Client;

use GuzzleHttp\ClientInterface;
use Zibios\WrikePhpGuzzle\Client\GuzzleClient;
use Zibios\WrikePhpGuzzle\Tests\TestCase;
use Zibios\WrikePhpLibrary\Enum\Api\RequestMethodEnum;

/**
 * Guzzle Client Test.
 */
class GuzzleClientTest extends TestCase
{
    /**
     * Test exception inheritance.
     */
    public function test_ExtendProperClasses()
    {
        $client = new GuzzleClient();
        self::assertInstanceOf(GuzzleClient::class, $client);
        self::assertInstanceOf(ClientInterface::class, $client);
    }

    public function test_setBearerToken()
    {
        $testBearerToken = 'test';
        $client = new GuzzleClient();

        self::assertSame($client, $client->setBearerToken($testBearerToken));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_setWrongBearerToken()
    {
        $testBearerToken = null;
        $client = new GuzzleClient();

        self::assertSame($client, $client->setBearerToken($testBearerToken));
    }

    /**
     * @return array
     */
    public function executeRequestForParamsProvider()
    {
        $testUri = '/test/uri';
        $baseOptions['headers'] = ['Content-Type' => 'application/json'];
        $baseOptions['base_uri'] = 'https://www.wrike.com/api/v3/';

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
     * @param array  $params
     * @param array  $options
     *
     * @dataProvider executeRequestForParamsProvider
     */
    public function test_executeRequestForParams($bearerToken, $requestMethod, $path, $params, $options)
    {
        /** @var GuzzleClient $clientMock */
        $clientMock = self::getMock(GuzzleClient::class, ['request']);
        $clientMock->expects(self::any())
            ->method('request')
            ->with(self::equalTo($requestMethod), self::equalTo($path), self::equalTo($options));
        $clientMock->setBearerToken($bearerToken);

        $clientMock->executeRequestForParams($requestMethod, $path, $params);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_executeRequestForParamsWithException()
    {
        $clientMock = new GuzzleClient();

        $clientMock->executeRequestForParams('wrong', 'path', ['test' => 'value']);
    }
}
