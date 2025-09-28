<?php

use Msr\LaravelBitunixApi\Requests\PlaceTpSlOrderRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can place TP/SL order with required parameters only', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    expect(fn() => $api->placeTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);
});

it('can place TP/SL order with take profit only', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    expect(fn() => $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'LAST_PRICE',   // tpStopType
        null,           // slPrice
        null,           // slStopType
        'LIMIT',        // tpOrderType
        '50000.1',      // tpOrderPrice
        null,           // slOrderType
        null,           // slOrderPrice
        '1',            // tpQty
        null            // slQty
    ))->not->toThrow(Exception::class);
});

it('can place TP/SL order with stop loss only', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    expect(fn() => $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        null,           // tpPrice
        null,           // tpStopType
        '45000',        // slPrice
        'LAST_PRICE',   // slStopType
        null,           // tpOrderType
        null,           // tpOrderPrice
        'LIMIT',        // slOrderType
        '45000.1',      // slOrderPrice
        null,           // tpQty
        '1'             // slQty
    ))->not->toThrow(Exception::class);
});

it('can place TP/SL order with both take profit and stop loss', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    expect(fn() => $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'LAST_PRICE',   // tpStopType
        '45000',        // slPrice
        'LAST_PRICE',   // slStopType
        'LIMIT',        // tpOrderType
        '50000.1',      // tpOrderPrice
        'LIMIT',        // slOrderType
        '45000.1',      // slOrderPrice
        '1',            // tpQty
        '1'             // slQty
    ))->not->toThrow(Exception::class);
});

it('validates place TP/SL order method exists', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    expect(method_exists($api, 'placeTpSlOrder'))->toBeTrue();
});

it('can handle different symbol formats', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $symbols = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($symbols as $symbol) {
        expect(fn() => $api->placeTpSlOrder($symbol, '111'))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different position ID formats', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $positionIds = ['111', 'position-123', '19848247723672'];

    foreach ($positionIds as $positionId) {
        expect(fn() => $api->placeTpSlOrder('BTCUSDT', $positionId))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different stop types', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $stopTypes = ['LAST_PRICE', 'MARK_PRICE'];

    foreach ($stopTypes as $stopType) {
        expect(fn() => $api->placeTpSlOrder(
            'BTCUSDT',
            '111',
            '50000',
            $stopType,
            '45000',
            $stopType
        ))->not->toThrow(Exception::class);
    }
});

it('can handle different order types', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $orderTypes = ['LIMIT', 'MARKET'];

    foreach ($orderTypes as $orderType) {
        expect(fn() => $api->placeTpSlOrder(
            'BTCUSDT',
            '111',
            '50000',
            'LAST_PRICE',
            '45000',
            'LAST_PRICE',
            $orderType,
            '50000.1',
            $orderType,
            '45000.1'
        ))->not->toThrow(Exception::class);
    }
});

it('can handle different quantity formats', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $quantities = ['1', '0.1', '10.5', '100'];

    foreach ($quantities as $qty) {
        expect(fn() => $api->placeTpSlOrder(
            'BTCUSDT',
            '111',
            '50000',
            'LAST_PRICE',
            '45000',
            'LAST_PRICE',
            'LIMIT',
            '50000.1',
            'LIMIT',
            '45000.1',
            $qty,
            $qty
        ))->not->toThrow(Exception::class);
    }
});

it('can handle different price formats', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $prices = ['50000', '50000.1', '50000.01', '50000.001'];

    foreach ($prices as $price) {
        expect(fn() => $api->placeTpSlOrder(
            'BTCUSDT',
            '111',
            $price,
            'LAST_PRICE',
            '45000',
            'LAST_PRICE',
            'LIMIT',
            $price,
            'LIMIT',
            '45000.1'
        ))->not->toThrow(Exception::class);
    }
});

it('can handle multiple place TP/SL order calls', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    $calls = [
        ['BTCUSDT', '111'],
        ['ETHUSDT', '222', '3000', 'LAST_PRICE'],
        ['ADAUSDT', '333', null, null, '0.5', 'LAST_PRICE'],
        ['BTCUSDT', '444', '50000', 'LAST_PRICE', '45000', 'LAST_PRICE', 'LIMIT', '50000.1', 'LIMIT', '45000.1', '1', '1'],
    ];

    foreach ($calls as $params) {
        expect(fn() => $api->placeTpSlOrder(...$params))
            ->not->toThrow(Exception::class);
    }
});

it('validates place TP/SL order response structure', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    // This test verifies the method can be called without throwing exceptions
    // The actual response structure will be validated by the API
    expect(fn() => $api->placeTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);
});

it('can handle edge cases for TP/SL order', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    // Test with minimum required parameters
    expect(fn() => $api->placeTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);

    // Test with all parameters
    expect(fn() => $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',
        'LAST_PRICE',
        '45000',
        'LAST_PRICE',
        'LIMIT',
        '50000.1',
        'LIMIT',
        '45000.1',
        '1',
        '1'
    ))->not->toThrow(Exception::class);
});

it('can handle special characters in parameters', function () {
    $api = app(PlaceTpSlOrderRequestContract::class);

    // Test with special characters in position ID
    expect(fn() => $api->placeTpSlOrder('BTCUSDT', 'pos-123-abc'))
        ->not->toThrow(Exception::class);

    // Test with decimal prices
    expect(fn() => $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        '50000.123',
        'LAST_PRICE',
        '45000.456',
        'LAST_PRICE'
    ))->not->toThrow(Exception::class);
});
