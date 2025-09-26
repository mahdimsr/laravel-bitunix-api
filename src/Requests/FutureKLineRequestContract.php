<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface FutureKLineRequestContract
{
    /**
     * @param string $symbol
     * @param string $interval
     * @param int $limit
     * @param int|null $startTime
     * @param int|null $endTime
     * @param string $type
     * @return ResponseInterface
     *
     * interval could be: 1m 5m 15m 30m 1h 2h 4h 6h 8h 12h 1d 3d 1w 1M
     * limit: max is 200
     * startTime : milliseconds format
     * endTime : milliseconds format
     * type could be: LAST_PRICE, MARK_PRICE
     */
    public function getFutureKline(string $symbol,
                             string $interval,
                             int    $limit = 100,
                             ?int   $startTime = null,
                             ?int   $endTime = null,
                             string $type = 'LAST_PRICE'): ResponseInterface;
}
