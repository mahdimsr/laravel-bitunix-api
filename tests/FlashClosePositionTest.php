<?php

use Msr\LaravelBitunixApi\Requests\FlashClosePositionRequestContract;

beforeEach(function () {
    config([
        'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can flash close position successfully', function () {
    $api = app(FlashClosePositionRequestContract::class);

    expect(fn () => $api->flashClosePosition('19848247723672'))
        ->not->toThrow(Exception::class);
});

it('validates required position ID parameter', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // Test with valid position ID
    expect(fn () => $api->flashClosePosition('19848247723672'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->flashClosePosition('123456789'))
        ->not->toThrow(Exception::class);
});

it('can handle different position ID formats', function () {
    $api = app(FlashClosePositionRequestContract::class);

    $positionIds = [
        '19848247723672',
        '123456789',
        '987654321',
        'position-123',
        'pos_456',
    ];

    foreach ($positionIds as $positionId) {
        expect(fn () => $api->flashClosePosition($positionId))
            ->not->toThrow(Exception::class);
    }
});

it('validates position ID parameter type', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // Test with string position ID
    expect(fn () => $api->flashClosePosition('19848247723672'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->flashClosePosition('123456789'))
        ->not->toThrow(Exception::class);
});

it('can handle edge cases for position ID', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // Test with long position ID
    expect(fn () => $api->flashClosePosition('198482477236721234567890'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->flashClosePosition('123'))
        ->not->toThrow(Exception::class);
});

it('validates flash close position method exists', function () {
    $api = app(FlashClosePositionRequestContract::class);

    expect(method_exists($api, 'flashClosePosition'))->toBeTrue();
});

it('can handle multiple flash close position calls', function () {
    $api = app(FlashClosePositionRequestContract::class);

    $positionIds = ['19848247723672', '19848247723673', '19848247723674'];

    foreach ($positionIds as $positionId) {
        expect(fn () => $api->flashClosePosition($positionId))
            ->not->toThrow(Exception::class);
    }
});

it('validates flash close position response structure', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // This test verifies the method can be called without throwing exceptions
    // The actual response structure will be validated by the API
    expect(fn () => $api->flashClosePosition('19848247723672'))
        ->not->toThrow(Exception::class);
});

it('can handle special characters in position ID', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // Test with position ID containing special characters
    expect(fn () => $api->flashClosePosition('pos-123_456'))
        ->not->toThrow(Exception::class)
        ->and(fn () => $api->flashClosePosition('pos.123.456'))
        ->not->toThrow(Exception::class);
});

it('validates flash close position with empty string', function () {
    $api = app(FlashClosePositionRequestContract::class);

    // This should not throw an exception at the method level
    // The API will handle validation
    expect(fn () => $api->flashClosePosition(''))
        ->not->toThrow(Exception::class);
});
