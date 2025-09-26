<?php

namespace Msr\LaravelBitunixApi;

use GuzzleHttp\Client;
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;
use Psr\Http\Message\ResponseInterface;

class LaravelBitunixApi implements FutureKLineRequestContract
{
    private Client $publicFutureClient;

    public function __construct()
    {
        $this->publicFutureClient = new Client([
            'base_uri' => config('bitunix-api.future_base_uri') . '/api/v1/futures/market/',
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
            ]
        ]);

        return $response;
    }
}
