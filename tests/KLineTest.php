<?php

use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;

it('check kline request response code', function () {

    $bootedClass = app(FutureKLineRequestContract::class);
    $response = $bootedClass->getFutureKline('BTCUSDT', '1h', 100, now()->subHours(6)->milliseconds, now()->milliseconds);
    expect($response->getStatusCode())
        ->toBe(200)
        ->and(json_decode($response->getBody()->getContents()))
        ->toHaveKeys(['code', 'data', 'msg']);
});
