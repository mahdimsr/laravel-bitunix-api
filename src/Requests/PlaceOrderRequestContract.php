<?php

namespace Msr\LaravelBitunixApi\Requests;

use Psr\Http\Message\ResponseInterface;

interface PlaceOrderRequestContract
{
    /**
     * Place a new order
     *
     * @param string $symbol Trading pair (e.g., 'BTCUSDT')
     * @param string $qty Amount (base coin)
     * @param string $side Order direction ('BUY' or 'SELL')
     * @param string $tradeSide Direction ('OPEN' or 'CLOSE')
     * @param string $orderType Order type ('LIMIT' or 'MARKET')
     * @param string|null $price Price of the order (required for LIMIT orders)
     * @param string|null $positionId Position ID (required when tradeSide is 'CLOSE')
     * @param string|null $effect Order expiration date
     * @param string|null $clientId Customize order ID
     * @param bool|null $reduceOnly Whether to just reduce the position
     * @param string|null $tpPrice Take profit trigger price
     * @param string|null $tpStopType Take profit trigger type
     * @param string|null $tpOrderType Take profit trigger place order type
     * @param string|null $tpOrderPrice Take profit trigger place order price
     * @param string|null $slPrice Stop loss trigger price
     * @param string|null $slStopType Stop loss trigger type
     * @param string|null $slOrderType Stop loss trigger place order type
     * @param string|null $slOrderPrice Stop loss trigger place order price
     * @return ResponseInterface
     */
    public function placeOrder(
        string $symbol,
        string $qty,
        string $side,
        string $tradeSide,
        string $orderType,
        ?string $price = null,
        ?string $positionId = null,
        ?string $effect = null,
        ?string $clientId = null,
        ?bool $reduceOnly = null,
        ?string $tpPrice = null,
        ?string $tpStopType = null,
        ?string $tpOrderType = null,
        ?string $tpOrderPrice = null,
        ?string $slPrice = null,
        ?string $slStopType = null,
        ?string $slOrderType = null,
        ?string $slOrderPrice = null
    ): ResponseInterface;
}
