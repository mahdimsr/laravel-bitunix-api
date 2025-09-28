<?php

namespace Msr\LaravelBitunixApi;

use GuzzleHttp\Client;
use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;
use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;
use Msr\LaravelBitunixApi\Requests\Header;
use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;
use Psr\Http\Message\ResponseInterface;

class LaravelBitunixApi implements ChangeLeverageRequestContract, ChangeMarginModeRequestContract, FutureKLineRequestContract, PlaceOrderRequestContract
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
        $bodyString = json_encode($body);
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
}
