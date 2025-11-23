<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface GetTradingPairsRequestContract
{
    /**
     * Get future trading pair details
     *
     * @param string|null $symbols Trading pairs, comma-separated (e.g., "BTCUSDT,ETHUSDT,XRPUSDT")
     * @return ResponseInterface
     */
    public function getTradingPairs(?string $symbols = null): ResponseInterface;
}

