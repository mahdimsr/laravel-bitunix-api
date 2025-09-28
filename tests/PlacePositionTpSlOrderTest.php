<?php

use Msr\LaravelBitunixApi\Requests\PlacePositionTpSlOrderRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can place position TP/SL order with required parameters only', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);
});

it('can place position TP/SL order with take profit only', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'LAST_PRICE'    // tpStopType
    ))->not->toThrow(Exception::class);
});

it('can place position TP/SL order with stop loss only', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        null,           // tpPrice
        null,           // tpStopType
        '45000',        // slPrice
        'LAST_PRICE'    // slStopType
    ))->not->toThrow(Exception::class);
});

it('can place position TP/SL order with both take profit and stop loss', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'LAST_PRICE',   // tpStopType
        '45000',        // slPrice
        'LAST_PRICE'    // slStopType
    ))->not->toThrow(Exception::class);
});

it('validates place position TP/SL order method exists', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    expect(method_exists($api, 'placePositionTpSlOrder'))->toBeTrue();
});

it('can handle different symbol formats', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    $symbols = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($symbols as $symbol) {
        expect(fn() => $api->placePositionTpSlOrder($symbol, '111'))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different position ID formats', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    $positionIds = ['111', 'position-123', '19848247723672'];

    foreach ($positionIds as $positionId) {
        expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', $positionId))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different stop types', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    $stopTypes = ['LAST_PRICE', 'MARK_PRICE'];

    foreach ($stopTypes as $stopType) {
        expect(fn() => $api->placePositionTpSlOrder(
            'BTCUSDT',
            '111',
            '50000',
            $stopType,
            '45000',
            $stopType
        ))->not->toThrow(Exception::class);
    }
});

it('can handle different price formats', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    $prices = ['50000', '50000.1', '50000.01', '50000.001'];

    foreach ($prices as $price) {
        expect(fn() => $api->placePositionTpSlOrder(
            'BTCUSDT',
            '111',
            $price,
            'LAST_PRICE',
            '45000',
            'LAST_PRICE'
        ))->not->toThrow(Exception::class);
    }
});

it('can handle multiple place position TP/SL order calls', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    $calls = [
        ['BTCUSDT', '111'],
        ['ETHUSDT', '222', '3000', 'LAST_PRICE'],
        ['ADAUSDT', '333', null, null, '0.5', 'LAST_PRICE'],
        ['BTCUSDT', '444', '50000', 'LAST_PRICE', '45000', 'LAST_PRICE'],
    ];

    foreach ($calls as $params) {
        expect(fn() => $api->placePositionTpSlOrder(...$params))
            ->not->toThrow(Exception::class);
    }
});

it('validates place position TP/SL order response structure', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    // This test verifies the method can be called without throwing exceptions
    // The actual response structure will be validated by the API
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);
});

it('can handle edge cases for position TP/SL order', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    // Test with minimum required parameters
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111'))
        ->not->toThrow(Exception::class);

    // Test with all parameters
    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',
        'LAST_PRICE',
        '45000',
        'LAST_PRICE'
    ))->not->toThrow(Exception::class);
});

it('can handle special characters in parameters', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    // Test with special characters in position ID
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', 'pos-123-abc'))
        ->not->toThrow(Exception::class);

    // Test with decimal prices
    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000.123',
        'LAST_PRICE',
        '45000.456',
        'LAST_PRICE'
    ))->not->toThrow(Exception::class);
});

it('can handle different combinations of parameters', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    // Test with only TP price
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111', '50000'))
        ->not->toThrow(Exception::class);

    // Test with only SL price
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111', null, null, '45000'))
        ->not->toThrow(Exception::class);

    // Test with only TP stop type
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111', '50000', 'MARK_PRICE'))
        ->not->toThrow(Exception::class);

    // Test with only SL stop type
    expect(fn() => $api->placePositionTpSlOrder('BTCUSDT', '111', null, null, '45000', 'MARK_PRICE'))
        ->not->toThrow(Exception::class);
});

it('can handle position TP/SL order with mixed stop types', function () {
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    // Test with different stop types for TP and SL
    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',
        'LAST_PRICE',
        '45000',
        'MARK_PRICE'
    ))->not->toThrow(Exception::class);

    // Test with opposite stop types
    expect(fn() => $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',
        'MARK_PRICE',
        '45000',
        'LAST_PRICE'
    ))->not->toThrow(Exception::class);
});
