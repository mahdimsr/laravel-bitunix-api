<?php

use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can place a basic market order', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'MARKET'
    ))->not->toThrow(Exception::class);
});

it('can place a limit order with price', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'LIMIT',
        '50000'
    ))->not->toThrow(Exception::class);
});

it('can place an order with all optional parameters', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'LIMIT',
        '50000',
        '12345',
        'GTC',
        'custom-client-id',
        false,
        '51000',
        'MARK_PRICE',
        'LIMIT',
        '51000.1',
        '49000',
        'MARK_PRICE',
        'LIMIT',
        '49000.1'
    ))->not->toThrow(Exception::class);
});

it('can place a close position order', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'SELL',
        'CLOSE',
        'MARKET',
        null,
        'position-123'
    ))->not->toThrow(Exception::class);
});

it('validates required parameters for place order', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder('BTCUSDT', '0.1', 'BUY', 'OPEN', 'MARKET'))
        ->not->toThrow(Exception::class)
        ->and(fn() => $api->placeOrder('BTCUSDT', '0.1', 'SELL', 'OPEN', 'MARKET'))
        ->not->toThrow(Exception::class);
});

it('handles different order types correctly', function () {
    $api = app(PlaceOrderRequestContract::class);

    $orderTypes = ['LIMIT', 'MARKET'];

    foreach ($orderTypes as $orderType) {
        expect(fn() => $api->placeOrder(
            'BTCUSDT',
            '0.1',
            'BUY',
            'OPEN',
            $orderType,
            $orderType === 'LIMIT' ? '50000' : null
        ))->not->toThrow(Exception::class);
    }
});

it('handles different trade sides correctly', function () {
    $api = app(PlaceOrderRequestContract::class);

    $tradeSides = ['OPEN', 'CLOSE'];

    foreach ($tradeSides as $tradeSide) {
        expect(fn() => $api->placeOrder(
            'BTCUSDT',
            '0.1',
            'BUY',
            $tradeSide,
            'MARKET',
            null,
            $tradeSide === 'CLOSE' ? 'position-123' : null
        ))->not->toThrow(Exception::class);
    }
});

it('can handle different trading pairs', function () {
    $api = app(PlaceOrderRequestContract::class);

    $tradingPairs = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($tradingPairs as $symbol) {
        expect(fn() => $api->placeOrder($symbol, '0.1', 'BUY', 'OPEN', 'MARKET'))
            ->not->toThrow(Exception::class);
    }
});

it('validates order side parameter values', function () {
    $api = app(PlaceOrderRequestContract::class);

    $sides = ['BUY', 'SELL'];

    foreach ($sides as $side) {
        expect(fn() => $api->placeOrder('BTCUSDT', '0.1', $side, 'OPEN', 'MARKET'))
            ->not->toThrow(Exception::class);
    }
});

it('validates trade side parameter values', function () {
    $api = app(PlaceOrderRequestContract::class);

    $tradeSides = ['OPEN', 'CLOSE'];

    foreach ($tradeSides as $tradeSide) {
        expect(fn() => $api->placeOrder(
            'BTCUSDT',
            '0.1',
            'BUY',
            $tradeSide,
            'MARKET',
            null,
            $tradeSide === 'CLOSE' ? 'position-123' : null
        ))->not->toThrow(Exception::class);
    }
});

it('can handle take profit and stop loss parameters', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'LIMIT',
        '50000',
        null,
        'GTC',
        null,
        false,
        '51000',
        'MARK_PRICE',
        'LIMIT',
        '51000.1',
        '49000',
        'MARK_PRICE',
        'LIMIT',
        '49000.1'
    ))->not->toThrow(Exception::class);
});

it('can handle reduce only orders', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'SELL',
        'CLOSE',
        'MARKET',
        null,
        'position-123',
        null,
        null,
        true
    ))->not->toThrow(Exception::class);
});

it('can handle custom client ID', function () {
    $api = app(PlaceOrderRequestContract::class);

    expect(fn() => $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'MARKET',
        null,
        null,
        null,
        'custom-order-123'
    ))->not->toThrow(Exception::class);
});

it('can handle different effect types', function () {
    $api = app(PlaceOrderRequestContract::class);

    $effects = ['IOC', 'FOK', 'GTC', 'POST_ONLY'];

    foreach ($effects as $effect) {
        expect(fn() => $api->placeOrder(
            'BTCUSDT',
            '0.1',
            'BUY',
            'OPEN',
            'LIMIT',
            '50000',
            null,
            $effect
        ))->not->toThrow(Exception::class);
    }
});

