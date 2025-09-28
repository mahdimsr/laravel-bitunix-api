<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface PlacePositionTpSlOrderRequestContract
{
    /**
     * Place Position TP/SL Order
     * When triggered, it will close the position at market price based on the position quantity at that time.
     * Each position can only have one Position TP/SL Order
     *
     * @param  string  $symbol  Trading pair
     * @param  string  $positionId  Position ID associated with take-profit and stop-loss
     * @param  string|null  $tpPrice  Take-profit trigger price
     * @param  string|null  $tpStopType  Take-profit trigger type (LAST_PRICE/MARK_PRICE)
     * @param  string|null  $slPrice  Stop-loss trigger price
     * @param  string|null  $slStopType  Stop-loss trigger type (LAST_PRICE/MARK_PRICE)
     */
    public function placePositionTpSlOrder(
        string $symbol,
        string $positionId,
        ?string $tpPrice = null,
        ?string $tpStopType = null,
        ?string $slPrice = null,
        ?string $slStopType = null
    ): ResponseInterface;
}
