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
use Zibios\WrikePhpLibrary\Enum\Api\ResponseFormatEnum;

/**
 * Guzzle Client Test.
 */
class GuzzleClientTest extends TestCase
{
    /**
     * Test exception inheritance.
     */
    public function test_ExtendProperClasses(): void
    {
        $client = new GuzzleClient();
        self::assertInstanceOf(GuzzleClient::class, $client);
        self::assertInstanceOf(ClientInterface::class, $client);
    }

    /**
     * Test getResponseFormat.
     */
    public function test_getResponseFormat(): void
    {
        $client = new GuzzleClient();
        self::assertSame(ResponseFormatEnum::PSR_RESPONSE, $client->getResponseFormat());
    }

    /**
     * @return array
     */
    public function paramsProvider(): array
    {
        $accessToken = 'testBearerToken';
        $testUri = '/test/uri';
        $baseOptions['base_uri'] = 'https://www.wrike.com/api/v3/';
        $baseOptions['headers']['Authorization'] = sprintf('Bearer %s', $accessToken);

        return [
            // [accessToken, requestMethod, path, params, options]
            [$accessToken, RequestMethodEnum::GET, $testUri, [], $baseOptions],
            [$accessToken, RequestMethodEnum::GET, $testUri, ['test' => 'query'], ['query' => ['test' => 'query']] + $baseOptions],
            [$accessToken, RequestMethodEnum::DELETE, $testUri, [], $baseOptions],
            [$accessToken, RequestMethodEnum::DELETE, $testUri, ['test' => 'query'], ['query' => ['test' => 'query']] + $baseOptions],
            [$accessToken, RequestMethodEnum::PUT, $testUri, [], $baseOptions],
            [$accessToken, RequestMethodEnum::PUT, $testUri, ['test' => 'query'], ['form_params' => ['test' => 'query']] + $baseOptions],
            [$accessToken, RequestMethodEnum::POST, $testUri, [], $baseOptions],
            [$accessToken, RequestMethodEnum::POST, $testUri, ['test' => 'query'], ['form_params' => ['test' => 'query']] + $baseOptions],
            [$accessToken, RequestMethodEnum::UPLOAD, $testUri, [], $baseOptions],
            [$accessToken, RequestMethodEnum::UPLOAD, $testUri,
                ['name' => 'name', 'resource' => 'resource'],
                [
                    'multipart' => [
                        [
                            'contents' => 'resource',
                            'name' => 'name',
                        ],
                    ],
                    'headers' => [
                        'X-File-Name' => 'name',
                        'Authorization' => sprintf('Bearer %s', $accessToken),
                    ],
                ] + $baseOptions,
            ],
        ];
    }

    /**
     * @param string $accessToken
     * @param string $requestMethod
     * @param string $path
     * @param array  $params
     * @param array  $options
     *
     * @dataProvider paramsProvider
     */
    public function test_executeRequestForParams($accessToken, $requestMethod, $path, $params, $options): void
    {
        /** @var GuzzleClient|\PHPUnit_Framework_MockObject_MockObject $clientMock */
        $clientMock = $this->getMockBuilder(GuzzleClient::class)->setMethods(['request'])->getMock();
        $clientMock->expects(self::any())
            ->method('request')
            ->with(self::equalTo(RequestMethodEnum::UPLOAD === $requestMethod ? RequestMethodEnum::POST : $requestMethod), self::equalTo($path), self::equalTo($options));

        $clientMock->executeRequestForParams($requestMethod, $path, $params, $accessToken);
    }

    /**
     * @return array
     */
    public function wrongParamsProvider(): array
    {
        $accessToken = 'testBearerToken';
        $testUri = '/test/uri';
        $baseOptions['base_uri'] = 'https://www.wrike.com/api/v3/';
        $baseOptions['headers']['Authorization'] = sprintf('Bearer %s', $accessToken);

        return [
            // [accessToken, requestMethod, path, params, options]
            ['', RequestMethodEnum::GET, $testUri, [], $baseOptions],
            [null, RequestMethodEnum::GET, $testUri, [], $baseOptions],
            [$accessToken, 'WRONG_METHOD', $testUri, [], $baseOptions],
        ];
    }

    /**
     * @param string $accessToken
     * @param string $requestMethod
     * @param string $path
     * @param array  $params
     * @param array  $options
     *
     * @dataProvider wrongParamsProvider
     */
    public function test_executeRequestForWrongParams($accessToken, $requestMethod, $path, $params, $options): void
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        /** @var GuzzleClient|\PHPUnit_Framework_MockObject_MockObject $clientMock */
        $clientMock = $this->getMockBuilder(GuzzleClient::class)->setMethods(['request'])->getMock();
        $clientMock->expects(self::any())
            ->method('request')
            ->with(self::equalTo($requestMethod), self::equalTo($path), self::equalTo($options));

        $clientMock->executeRequestForParams($requestMethod, $path, $params, $accessToken);
    }
}
