<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface GetSingleAccountRequestContract
{
    /**
     * Get account details with the given margin coin
     *
     * @param  string  $marginCoin  Margin coin (e.g., 'USDT')
     */
    public function getSingleAccount(string $marginCoin): ResponseInterface;
}
