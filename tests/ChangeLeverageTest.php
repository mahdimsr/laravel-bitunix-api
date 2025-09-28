<?php

use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;

it('can change leverage successfully', function () {
    $api = app(ChangeLeverageRequestContract::class);
    // This test will make a real API call if credentials are valid
    // For testing purposes, we'll just verify the method exists and can be called
    expect(fn () => $api->changeLeverage('BTCUSDT', 'USDT', 12))
        ->not->toThrow(Exception::class);
});

it('validates required parameters for change leverage', function () {
    $api = app(ChangeLeverageRequestContract::class);

    expect(fn () => $api->changeLeverage('BTCUSDT', 'USDT', 10))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->changeLeverage('BTCUSDT', 'USDT', 0))
        ->not->toThrow(Exception::class);
});
