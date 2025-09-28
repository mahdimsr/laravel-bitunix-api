<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface ChangeLeverageRequestContract
{
    /**
     * Change leverage for a trading pair
     *
     * @param string $symbol Trading pair (e.g., 'BTCUSDT')
     * @param string $marginCoin Margin coin (e.g., 'USDT')
     * @param int $leverage Leverage value
     * @return ResponseInterface
     */
    public function changeLeverage(string $symbol, string $marginCoin, int $leverage): ResponseInterface;
}

