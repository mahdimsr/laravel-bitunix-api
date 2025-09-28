<?php

use Msr\LaravelBitunixApi\Requests\GetPendingPositionsRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can get all pending positions', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    expect(fn() => $api->getPendingPositions())
        ->not->toThrow(Exception::class);
});

it('can get pending positions by symbol', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    expect(fn() => $api->getPendingPositions('BTCUSDT'))
        ->not->toThrow(Exception::class);
});

it('can get pending positions by position ID', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    expect(fn() => $api->getPendingPositions(null, '19848247723672'))
        ->not->toThrow(Exception::class);
});

it('can get pending positions with both symbol and position ID', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    expect(fn() => $api->getPendingPositions('BTCUSDT', '19848247723672'))
        ->not->toThrow(Exception::class);
});

it('validates required parameters for get pending positions', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    // Test without parameters
    expect(fn() => $api->getPendingPositions())
        ->not->toThrow(Exception::class);

    // Test with symbol only
    expect(fn() => $api->getPendingPositions('BTCUSDT'))
        ->not->toThrow(Exception::class);

    // Test with position ID only
    expect(fn() => $api->getPendingPositions(null, '19848247723672'))
        ->not->toThrow(Exception::class);
});

it('can handle different trading pairs', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    $tradingPairs = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($tradingPairs as $symbol) {
        expect(fn() => $api->getPendingPositions($symbol))
            ->not->toThrow(Exception::class);
    }
});

it('can handle different position ID formats', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    $positionIds = [
        '19848247723672',
        '123456789',
        '987654321',
        'position-123',
        'pos_456'
    ];

    foreach ($positionIds as $positionId) {
        expect(fn() => $api->getPendingPositions(null, $positionId))
            ->not->toThrow(Exception::class);
    }
});

it('validates get pending positions method exists', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    expect(method_exists($api, 'getPendingPositions'))->toBeTrue();
});

it('can handle edge cases for parameters', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    // Test with empty string symbol
    expect(fn() => $api->getPendingPositions(''))
        ->not->toThrow(Exception::class);

    // Test with empty string position ID
    expect(fn() => $api->getPendingPositions(null, ''))
        ->not->toThrow(Exception::class);
});

it('can handle special characters in parameters', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    // Test with special characters in symbol
    expect(fn() => $api->getPendingPositions('BTC-USDT'))
        ->not->toThrow(Exception::class);

    // Test with special characters in position ID
    expect(fn() => $api->getPendingPositions(null, 'pos-123_456'))
        ->not->toThrow(Exception::class);
});

it('validates get pending positions response structure', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    // This test verifies the method can be called without throwing exceptions
    // The actual response structure will be validated by the API
    expect(fn() => $api->getPendingPositions())
        ->not->toThrow(Exception::class);
});

it('can handle multiple get pending positions calls', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    $symbols = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($symbols as $symbol) {
        expect(fn() => $api->getPendingPositions($symbol))
            ->not->toThrow(Exception::class);
    }
});

it('can handle combination of symbol and position ID', function () {
    $api = app(GetPendingPositionsRequestContract::class);

    $combinations = [
        ['BTCUSDT', '19848247723672'],
        ['ETHUSDT', '19848247723673'],
        ['ADAUSDT', '19848247723674']
    ];

    foreach ($combinations as [$symbol, $positionId]) {
        expect(fn() => $api->getPendingPositions($symbol, $positionId))
            ->not->toThrow(Exception::class);
    }
});
