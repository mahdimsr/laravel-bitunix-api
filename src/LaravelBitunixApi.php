<?php

namespace Msr\LaravelBitunixApi;

use GuzzleHttp\Client;
use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;
use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;
use Msr\LaravelBitunixApi\Requests\FlashClosePositionRequestContract;
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;
use Msr\LaravelBitunixApi\Requests\GetPendingPositionsRequestContract;
use Msr\LaravelBitunixApi\Requests\GetSingleAccountRequestContract;
use Msr\LaravelBitunixApi\Requests\Header;
use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;
use Msr\LaravelBitunixApi\Requests\PlaceTpSlOrderRequestContract;
use Psr\Http\Message\ResponseInterface;

class LaravelBitunixApi implements ChangeLeverageRequestContract, ChangeMarginModeRequestContract, FlashClosePositionRequestContract, FutureKLineRequestContract, GetPendingPositionsRequestContract, GetSingleAccountRequestContract, PlaceOrderRequestContract, PlaceTpSlOrderRequestContract
{
    private Client $publicFutureClient;

    public function __construct()
    {
        $this->publicFutureClient = new Client([
            'base_uri' => config('bitunix-api.future_base_uri').'/api/v1/futures/market/',
        ]);
    }

    protected function getPrivateFutureClient(array $queryParams = [], array $body = []): Client
    {
        $bodyString = count($body) ? json_encode($body) : '';
        $headers = Header::generateHeaders($queryParams, $bodyString);

        return new Client([
            'base_uri' => config('bitunix-api.future_base_uri').'/api/v1/futures/',
            'headers' => $headers,
        ]);
    }

    public function getFutureKline(string $symbol, string $interval, int $limit = 100, ?int $startTime = null, ?int $endTime = null, string $type = 'LAST_PRICE'): ResponseInterface
    {
        $response = $this->publicFutureClient->get('kline', [
            'query' => [
                'symbol' => $symbol,
                'interval' => $interval,
                'limit' => $limit,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'type' => $type,
            ],
        ]);

        return $response;
    }

    public function changeLeverage(string $symbol, string $marginCoin, int $leverage): ResponseInterface
    {
        $body = [
            'symbol' => $symbol,
            'marginCoin' => $marginCoin,
            'leverage' => $leverage,
        ];

        $response = $this->getPrivateFutureClient([], $body)->post('account/change_leverage', [
            'json' => $body,
        ]);

        return $response;
    }

    public function changeMarginMode(string $symbol, string $marginCoin, string $marginMode): ResponseInterface
    {
        $body = [
            'symbol' => $symbol,
            'marginCoin' => $marginCoin,
            'marginMode' => $marginMode,
        ];

        $response = $this->getPrivateFutureClient([], $body)->post('account/change_margin_mode', [
            'json' => $body,
        ]);

        return $response;
    }

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
    ): ResponseInterface {
        $body = [
            'symbol' => $symbol,
            'qty' => $qty,
            'side' => $side,
            'tradeSide' => $tradeSide,
            'orderType' => $orderType,
        ];

        // Add optional parameters if provided
        if ($price !== null) {
            $body['price'] = $price;
        }
        if ($positionId !== null) {
            $body['positionId'] = $positionId;
        }
        if ($effect !== null) {
            $body['effect'] = $effect;
        }
        if ($clientId !== null) {
            $body['clientId'] = $clientId;
        }
        if ($reduceOnly !== null) {
            $body['reduceOnly'] = $reduceOnly;
        }
        if ($tpPrice !== null) {
            $body['tpPrice'] = $tpPrice;
        }
        if ($tpStopType !== null) {
            $body['tpStopType'] = $tpStopType;
        }
        if ($tpOrderType !== null) {
            $body['tpOrderType'] = $tpOrderType;
        }
        if ($tpOrderPrice !== null) {
            $body['tpOrderPrice'] = $tpOrderPrice;
        }
        if ($slPrice !== null) {
            $body['slPrice'] = $slPrice;
        }
        if ($slStopType !== null) {
            $body['slStopType'] = $slStopType;
        }
        if ($slOrderType !== null) {
            $body['slOrderType'] = $slOrderType;
        }
        if ($slOrderPrice !== null) {
            $body['slOrderPrice'] = $slOrderPrice;
        }

        $response = $this->getPrivateFutureClient([], $body)->post('trade/place_order', [
            'json' => $body,
        ]);

        return $response;
    }

    public function flashClosePosition(string $positionId): ResponseInterface
    {
        $body = [
            'positionId' => $positionId,
        ];

        $response = $this->getPrivateFutureClient([], $body)->post('trade/flash_close_position', [
            'json' => $body,
        ]);

        return $response;
    }

    public function getPendingPositions(?string $symbol = null, ?string $positionId = null): ResponseInterface
    {
        $queryParams = [];

        if ($symbol != null) {
            $queryParams['symbol'] = $symbol;
        }

        if ($positionId != null) {
            $queryParams['positionId'] = $positionId;
        }

        $response = $this->getPrivateFutureClient($queryParams, [])->get('position/get_pending_positions', [
            'query' => $queryParams,
        ]);

        return $response;
    }

    public function getSingleAccount(string $marginCoin): ResponseInterface
    {
        $queryParams = [
            'marginCoin' => $marginCoin,
        ];

        $response = $this->getPrivateFutureClient($queryParams, [])->get('account', [
            'query' => $queryParams,
        ]);

        return $response;
    }

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
    ): ResponseInterface {
        $body = [
            'symbol' => $symbol,
            'positionId' => $positionId,
        ];

        // Add optional parameters if provided
        if ($tpPrice !== null) {
            $body['tpPrice'] = $tpPrice;
        }
        if ($tpStopType !== null) {
            $body['tpStopType'] = $tpStopType;
        }
        if ($slPrice !== null) {
            $body['slPrice'] = $slPrice;
        }
        if ($slStopType !== null) {
            $body['slStopType'] = $slStopType;
        }
        if ($tpOrderType !== null) {
            $body['tpOrderType'] = $tpOrderType;
        }
        if ($tpOrderPrice !== null) {
            $body['tpOrderPrice'] = $tpOrderPrice;
        }
        if ($slOrderType !== null) {
            $body['slOrderType'] = $slOrderType;
        }
        if ($slOrderPrice !== null) {
            $body['slOrderPrice'] = $slOrderPrice;
        }
        if ($tpQty !== null) {
            $body['tpQty'] = $tpQty;
        }
        if ($slQty !== null) {
            $body['slQty'] = $slQty;
        }

        $response = $this->getPrivateFutureClient([], $body)->post('tpsl/place_order', [
            'json' => $body,
        ]);

        return $response;
    }
}
