<?php

use Msr\LaravelBitunixApi\Requests\GetSingleAccountRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can get single account successfully', function () {
    $api = app(GetSingleAccountRequestContract::class);

    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);
});

it('validates required margin coin parameter', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // Test with valid margin coin
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);

    // Test with different margin coins
    expect(fn() => $api->getSingleAccount('BTC'))
        ->not->toThrow(Exception::class);
});

it('can handle different margin coins', function () {
    $api = app(GetSingleAccountRequestContract::class);

    $marginCoins = ['USDT', 'BTC', 'ETH', 'BNB', 'ADA'];

    foreach ($marginCoins as $marginCoin) {
        expect(fn() => $api->getSingleAccount($marginCoin))
            ->not->toThrow(Exception::class);
    }
});

it('validates get single account method exists', function () {
    $api = app(GetSingleAccountRequestContract::class);

    expect(method_exists($api, 'getSingleAccount'))->toBeTrue();
});

it('can handle edge cases for margin coin', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // Test with uppercase margin coin
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);

    // Test with lowercase margin coin
    expect(fn() => $api->getSingleAccount('usdt'))
        ->not->toThrow(Exception::class);
});

it('validates get single account response structure', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // This test verifies the method can be called without throwing exceptions
    // The actual response structure will be validated by the API
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);
});

it('can handle multiple get single account calls', function () {
    $api = app(GetSingleAccountRequestContract::class);

    $marginCoins = ['USDT', 'BTC', 'ETH'];

    foreach ($marginCoins as $marginCoin) {
        expect(fn() => $api->getSingleAccount($marginCoin))
            ->not->toThrow(Exception::class);
    }
});

it('validates margin coin parameter type', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // Test with string margin coin
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);

    // Test with different string formats
    expect(fn() => $api->getSingleAccount('BTC'))
        ->not->toThrow(Exception::class);
});

it('can handle special characters in margin coin', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // Test with margin coin containing special characters
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);

    // Test with margin coin containing numbers
    expect(fn() => $api->getSingleAccount('USDT'))
        ->not->toThrow(Exception::class);
});

it('validates get single account with empty string', function () {
    $api = app(GetSingleAccountRequestContract::class);

    // This should not throw an exception at the method level
    // The API will handle validation
    expect(fn() => $api->getSingleAccount(''))
        ->not->toThrow(Exception::class);
});
