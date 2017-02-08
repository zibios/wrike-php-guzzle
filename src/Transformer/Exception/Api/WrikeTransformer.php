<?php
/**
 * This file is part of the WrikePhpLibrary package.
 *
 * (c) Zbigniew Ślązak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zibios\WrikePhpGuzzle\Transformer\Exception\Api;

use GuzzleHttp\Exception\BadResponseException;
use Zibios\WrikePhpLibrary\Exception\Api\ApiException;
use Zibios\WrikePhpLibrary\Transformer\AbstractApiExceptionTransformer;

/**
 * Wrike Transformer
 */
class WrikeTransformer extends AbstractApiExceptionTransformer
{
    /**
     * @param \Exception $exception
     *
     * @return \Exception|ApiException
     */
    public function transform(\Exception $exception)
    {
        if ($exception instanceof BadResponseException === false) {
            return new ApiException($exception);
        }

        try {
            /** @var BadResponseException $exception */
            $errorResponse = $exception->getResponse();
            $errorStatusCode = $errorResponse->getStatusCode();
            $bodyString = (string) $errorResponse->getBody();
            $bodyArray = json_decode($bodyString, true);
            $errorStatusName = '';
            if (is_array($bodyArray) && array_key_exists('error', $bodyArray)) {
                $errorStatusName = $bodyArray['error'];
            }
        } catch (\Exception $e) {
            return new ApiException($exception);
        }

        return $this->transformByStatusCodeAndName($exception, $errorStatusCode, $errorStatusName);
    }
}
