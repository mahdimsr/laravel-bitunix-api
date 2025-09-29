<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface PlaceTpSlOrderRequestContract
{
    /**
     * Place TP/SL Order
     *
     * @param  string  $symbol  Trading pair
     * @param  string  $positionId  Position ID associated with take-profit and stop-loss
     * @param  string|null  $tpPrice  Take-profit trigger price
     * @param  string|null  $tpStopType  Take-profit trigger type (LAST_PRICE/MARK_PRICE)
     * @param  string|null  $slPrice  Stop-loss trigger price
     * @param  string|null  $slStopType  Stop-loss trigger type (LAST_PRICE/MARK_PRICE)
     * @param  string|null  $tpOrderType  Take-profit order type (LIMIT/MARKET)
     * @param  string|null  $tpOrderPrice  Take-profit order price
     * @param  string|null  $slOrderType  Stop-loss order type (LIMIT/MARKET)
     * @param  string|null  $slOrderPrice  Stop-loss order price
     * @param  string|null  $tpQty  Take-profit order quantity (base coin)
     * @param  string|null  $slQty  Stop-loss order quantity (base coin)
     */
    public function placeTpSlOrder(
        string $symbol,
        string $positionId,
        ?string $tpPrice = null,
        ?string $tpStopType = null,
        ?string $slPrice = null,
        ?string $slStopType = null,
        ?string $tpOrderType = null,
        ?string $tpOrderPrice = null,
        ?string $slOrderType = null,
        ?string $slOrderPrice = null,
        ?string $tpQty = null,
        ?string $slQty = null
    ): ResponseInterface;
}
