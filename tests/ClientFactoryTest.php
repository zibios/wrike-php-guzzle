<?php
/**
 * This file is part of the WrikePhpGuzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Tests;

use GuzzleHttp\ClientInterface;
use Zibios\WrikePhpGuzzle\Client\GuzzleClient;
use Zibios\WrikePhpGuzzle\ClientFactory;
use Zibios\WrikePhpLibrary\Exception\Api\ApiException;

/**
 * Client Factory Test
 */
class ClientFactoryTest extends TestCase
{
    public function test_create()
    {
        $client = ClientFactory::create();
        self::assertInstanceOf(ClientInterface::class, $client);
        self::assertInstanceOf(GuzzleClient::class, $client);
        $normalizedException = $client->transformApiException(new \Exception());
        self::assertInstanceOf(ApiException::class, $normalizedException);
    }
}