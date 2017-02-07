<?php
/**
 * This file is part of the WrikePhpLibrary package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Tests\Transformer\Exception\Api;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Zibios\WrikePhpGuzzle\Transformer\Exception\Api\WrikeTransformer;
use Zibios\WrikePhpGuzzle\Tests\TestCase;
use Zibios\WrikePhpLibrary\Exception\Api\AccessForbiddenException;
use Zibios\WrikePhpLibrary\Exception\Api\ApiException;
use Zibios\WrikePhpLibrary\Exception\Api\InvalidParameterException;
use Zibios\WrikePhpLibrary\Exception\Api\InvalidRequestException;
use Zibios\WrikePhpLibrary\Exception\Api\MethodNotFoundException;
use Zibios\WrikePhpLibrary\Exception\Api\NotAllowedException;
use Zibios\WrikePhpLibrary\Exception\Api\NotAuthorizedException;
use Zibios\WrikePhpLibrary\Exception\Api\ParameterRequiredException;
use Zibios\WrikePhpLibrary\Exception\Api\ResourceNotFoundException;
use Zibios\WrikePhpLibrary\Exception\Api\ServerErrorException;

/**
 * Wrike Transformer Test
 */
class WrikeTransformerTest extends TestCase
{
    /**
     * @return array
     */
    public function responseExceptionsProvider()
    {
        return [
            // [errorStatusCode, errorStatusName, expectedExceptionClass]
            [444, 'something', ApiException::class],
            [555, 'something', ApiException::class],

            [401, 'wrong_error', ApiException::class],
            [402, 'wrong_error', ApiException::class],
            [403, 'wrong_error', ApiException::class],
            [404, 'wrong_error', ApiException::class],
            [500, 'wrong_error', ApiException::class],
            [501, 'wrong_error', ApiException::class],
            [502, 'wrong_error', ApiException::class],
            [503, 'wrong_error', ApiException::class],

            [403, 'access_forbidden', AccessForbiddenException::class],
            [400, 'invalid_parameter', InvalidParameterException::class],
            [400, 'invalid_request', InvalidRequestException::class],
            [404, 'method_not_found', MethodNotFoundException::class],
            [403, 'not_allowed', NotAllowedException::class],
            [401, 'not_authorized', NotAuthorizedException::class],
            [400, 'parameter_required', ParameterRequiredException::class],
            [404, 'resource_not_found', ResourceNotFoundException::class],
            [500, 'server_error', ServerErrorException::class],
        ];
    }

    /**
     * @param int $errorStatusCode
     * @param string $errorStatusName
     * @param string $expectedExceptionClass
     *
     * @dataProvider responseExceptionsProvider
     */
    public function test_wrikeExceptions($errorStatusCode, $errorStatusName, $expectedExceptionClass)
    {
        $transformer = new WrikeTransformer();

        $requestMock = new Request('get', 'http://google.com');
        $responseMock = new Response(
            $errorStatusCode,
            [],
            sprintf('{"errorDescription":"description", "error":"%s"}', $errorStatusName)
        );
        /** @var BadResponseException $exception */
        $exception = BadResponseException::create($requestMock, $responseMock);

        $normalizedException = $transformer->transform($exception);
        self::assertInstanceOf($expectedExceptionClass, $normalizedException, sprintf('"%s expected, "%s" received"', $expectedExceptionClass, get_class($normalizedException)));
        self::assertInstanceOf(ApiException::class, $normalizedException, sprintf('"%s expected, "%s" received"', ApiException::class, get_class($normalizedException)));
    }

    public function test_networkException()
    {
        $transformer = new WrikeTransformer();

        $testException = new TransferException();
        $normalizedException = $transformer->transform($testException);
        self::assertInstanceOf(ApiException::class, $normalizedException, sprintf('"%s expected, "%s" received"', ApiException::class, get_class($normalizedException)));

        $testException = new \Exception();
        $normalizedException = $transformer->transform($testException);
        self::assertInstanceOf(ApiException::class, $normalizedException, sprintf('"%s expected, "%s" received"', ApiException::class, get_class($normalizedException)));
    }

    /**
     * @return array
     */
    public function malformedBodyProvider()
    {
        return [
            // [errorStatusCode, body, expectedExceptionClass]
            [ServerErrorException::STATUS_CODE, sprintf('{"errorDescription":"description", "error":"%s"}', ServerErrorException::STATUS_NAME), ServerErrorException::class],
            [ServerErrorException::STATUS_CODE, sprintf('{"error":"%s"}', ServerErrorException::STATUS_NAME), ServerErrorException::class],
            [ServerErrorException::STATUS_CODE, sprintf('{"without_error_property":"%s"}', ServerErrorException::STATUS_NAME), ApiException::class],
            [ServerErrorException::STATUS_CODE, sprintf('{"error":" %s"}', ServerErrorException::STATUS_NAME), ApiException::class],
            [ServerErrorException::STATUS_CODE, '{}', ApiException::class],
            [ServerErrorException::STATUS_CODE, 'malformed json body', ApiException::class],
            [ServerErrorException::STATUS_CODE, null, ApiException::class],
        ];
    }

    /**
     * @param int $errorStatusCode
     * @param mixed $body
     * @param string $expectedExceptionClass
     *
     * @dataProvider malformedBodyProvider
     */
    public function test_malformedResponseBodyException($errorStatusCode, $body, $expectedExceptionClass)
    {
        $transformer = new WrikeTransformer();

        $requestMock = new Request('get', 'http://google.com');
        $responseMock = new Response(
            $errorStatusCode,
            [],
            $body
        );
        /** @var BadResponseException $exception */
        $exception = BadResponseException::create($requestMock, $responseMock);
        $normalizedException = $transformer->transform($exception);
        self::assertInstanceOf($expectedExceptionClass, $normalizedException, sprintf('"%s expected, "%s" received"', $expectedExceptionClass, get_class($normalizedException)));
    }
}
