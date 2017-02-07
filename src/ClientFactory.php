<?php
/**
 * This file is part of the WrikePhpGuzzle package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle;

use Zibios\WrikePhpGuzzle\Client\GuzzleClient;
use Zibios\WrikePhpGuzzle\Transformer\Exception\Api\WrikeTransformer;
use Zibios\WrikePhpLibrary\Api;

/**
 * Client Factory
 */
class ClientFactory
{
    /**
     * @return GuzzleClient
     * @throws \InvalidArgumentException
     */
    public static function create()
    {
        return new GuzzleClient(
            new WrikeTransformer(),
            ['base_uri' => Api::BASE_URI]
        );
    }
}
