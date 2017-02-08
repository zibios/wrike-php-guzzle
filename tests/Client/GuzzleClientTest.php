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
}
