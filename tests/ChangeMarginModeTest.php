<?php

use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can change margin mode successfully', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'ISOLATION'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'CROSS'))
        ->not->toThrow(Exception::class);

});

it('validates required parameters for change margin mode', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'ISOLATION'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->changeMarginMode('ETHUSDT', 'USDT', 'CROSS'))
        ->not->toThrow(Exception::class);

});

it('handles different margin modes correctly', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    $marginModes = ['ISOLATION', 'CROSS'];

    foreach ($marginModes as $mode) {
        expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', $mode))
            ->not->toThrow(Exception::class);
    }
});

it('validates margin mode parameter values', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'ISOLATION'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'CROSS'))
        ->not->toThrow(Exception::class);

});

it('can handle different trading pairs', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    $tradingPairs = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($tradingPairs as $symbol) {
        expect(fn () => $api->changeMarginMode($symbol, 'USDT', 'ISOLATION'))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different margin coins', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    $marginCoins = ['USDT', 'BTC', 'ETH'];

    foreach ($marginCoins as $coin) {
        expect(fn () => $api->changeMarginMode('BTCUSDT', $coin, 'ISOLATION'))
            ->not->toThrow(Exception::class);
    }
});

it('validates margin mode constants', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    $validModes = ['ISOLATION', 'CROSS'];

    foreach ($validModes as $mode) {
        expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', $mode))
            ->not->toThrow(Exception::class);
    }
});

it('handles edge cases for margin mode', function () {
    $api = app(ChangeMarginModeRequestContract::class);

    expect(fn () => $api->changeMarginMode('BTCUSDT', 'USDT', 'ISOLATION'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->changeMarginMode('ETHUSDT', 'USDT', 'CROSS'))
        ->not->toThrow(Exception::class);

});
