<?php

use Msr\LaravelBitunixApi\Requests\GetTradingPairsRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('check trading pairs request response code', function () {
    $bootedClass = app(GetTradingPairsRequestContract::class);
    $response = $bootedClass->getTradingPairs();
    expect($response->getStatusCode())
        ->toBe(200)
        ->and(json_decode($response->getBody()->getContents(), true))
        ->toHaveKeys(['code', 'data', 'msg']);
});

it('can get trading pairs with specific symbols', function () {
    $api = app(GetTradingPairsRequestContract::class);

    expect(fn () => $api->getTradingPairs('BTCUSDT,ETHUSDT'))
        ->not->toThrow(Exception::class);
});

it('can get trading pairs without symbols parameter', function () {
    $api = app(GetTradingPairsRequestContract::class);

    expect(fn () => $api->getTradingPairs())
        ->not->toThrow(Exception::class);
});

it('validates get trading pairs method exists', function () {
    $api = app(GetTradingPairsRequestContract::class);

    expect(method_exists($api, 'getTradingPairs'))->toBeTrue();
});

it('can handle single symbol', function () {
    $api = app(GetTradingPairsRequestContract::class);

    expect(fn () => $api->getTradingPairs('BTCUSDT'))
        ->not->toThrow(Exception::class);
});

it('can handle multiple symbols', function () {
    $api = app(GetTradingPairsRequestContract::class);

    $symbols = 'BTCUSDT,ETHUSDT,XRPUSDT';
    expect(fn () => $api->getTradingPairs($symbols))
        ->not->toThrow(Exception::class);
});

it('validates trading pairs response structure', function () {
    $api = app(GetTradingPairsRequestContract::class);

    $response = $api->getTradingPairs();
    $data = json_decode($response->getBody()->getContents(), true);

    expect($response->getStatusCode())->toBe(200)
        ->and($data)->toHaveKeys(['code', 'data', 'msg']);

    if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
        $firstPair = $data['data'][0];
        expect($firstPair)->toHaveKeys([
            'symbol',
            'base',
            'quote',
            'minTradeVolume',
            'minBuyPriceOffset',
            'maxSellPriceOffset',
            'maxLimitOrderVolume',
            'maxMarketOrderVolume',
            'basePrecision',
            'quotePrecision',
            'maxLeverage',
            'minLeverage',
            'defaultLeverage',
            'defaultMarginMode',
            'priceProtectScope',
            'symbolStatus',
        ]);
    }
});

it('can handle different symbol formats', function () {
    $api = app(GetTradingPairsRequestContract::class);

    // Test with uppercase symbols
    expect(fn () => $api->getTradingPairs('BTCUSDT'))
        ->not->toThrow(Exception::class);

    // Test with lowercase symbols
    expect(fn () => $api->getTradingPairs('btcusdt'))
        ->not->toThrow(Exception::class);
});

it('can handle empty symbols parameter', function () {
    $api = app(GetTradingPairsRequestContract::class);

    // Test with null (should return all trading pairs)
    expect(fn () => $api->getTradingPairs(null))
        ->not->toThrow(Exception::class);
});

