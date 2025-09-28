<?php

namespace Msr\LaravelBitunixApi;

use GuzzleHttp\Client;
use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;
use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;
use Msr\LaravelBitunixApi\Requests\Header;
use Psr\Http\Message\ResponseInterface;

class LaravelBitunixApi implements ChangeLeverageRequestContract, ChangeMarginModeRequestContract, FutureKLineRequestContract
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
}
