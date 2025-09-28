<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface ChangeMarginModeRequestContract
{
    /**
     * Change margin mode for a trading pair
     *
     * @param string $symbol Trading pair (e.g., 'BTCUSDT')
     * @param string $marginCoin Margin coin (e.g., 'USDT')
     * @param string $marginMode Margin mode ('ISOLATION' or 'CROSS')
     * @return ResponseInterface
     */
    public function changeMarginMode(string $symbol, string $marginCoin, string $marginMode): ResponseInterface;
}
