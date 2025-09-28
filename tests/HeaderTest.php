<?php

use Msr\LaravelBitunixApi\Requests\Header;

beforeEach(function () {
    config([
        'bitunix-api.api_key' => 'test-api-key',
        'bitunix-api.api_secret' => 'test-secret-key',
        'bitunix-api.language' => 'en-US',
    ]);
});

it('can sort query parameters in ascending ASCII order', function () {
    $parameters = [
        'uid' => '200',
        'id' => '1',
        'name' => 'test'
    ];
    
    $sorted = Header::sortQueryParameters($parameters);
    
    expect(array_keys($sorted))->toBe(['id', 'name', 'uid']);
    expect($sorted)->toBe([
        'id' => '1',
        'name' => 'test',
        'uid' => '200'
    ]);
});

it('can digest query parameters to string format', function () {
    $parameters = [
        'id' => '1',
        'uid' => '200'
    ];
    
    $result = Header::digestQueryParameters($parameters);
    
    expect($result)->toBe('id1uid200');
});

it('can generate a valid nonce', function () {
    $nonce = Header::generateNonce();
    
    expect($nonce)
        ->toBeString()
        ->toHaveLength(32)
        ->toMatch('/^[a-f0-9]+$/');
});

it('can generate timestamp in milliseconds', function () {
    $timestamp = Header::generateTimestamp();
    
    expect($timestamp)
        ->toBeString()
        ->toMatch('/^\d{13}$/'); // 13 digits for milliseconds
});

it('can generate signature according to Bitunix documentation', function () {
    $nonce = '123456';
    $timestamp = '20241120123045';
    $queryParams = ['id' => '1', 'uid' => '200'];
    $body = '{"uid":"2899","arr":[{"id":1,"name":"maple"},{"id":2,"name":"lily"}]}';
    
    $sign = Header::generateSignValue($queryParams, $body, $nonce, $timestamp);
    
    expect($sign)
        ->toBeString()
        ->toHaveLength(64) // SHA256 hex length
        ->toMatch('/^[a-f0-9]+$/');
});

it('can generate complete headers for authenticated requests', function () {
    $queryParams = ['symbol' => 'BTCUSDT'];
    $body = '{"symbol":"BTCUSDT","marginCoin":"USDT","leverage":12}';
    
    $headers = Header::generateHeaders($queryParams, $body);
    
    expect($headers)
        ->toHaveKeys(['api-key', 'sign', 'nonce', 'timestamp', 'language', 'Content-Type'])
        ->and($headers['api-key'])->toBe('test-api-key')
        ->and($headers['sign'])->toBeString()
        ->and($headers['nonce'])->toBeString()
        ->and($headers['timestamp'])->toBeString()
        ->and($headers['language'])->toBe('en-US')
        ->and($headers['Content-Type'])->toBe('application/json');
});

it('throws exception when API credentials are missing', function () {
    config([
        'bitunix-api.api_key' => '',
        'bitunix-api.api_secret' => '',
    ]);
    
    expect(fn() => Header::generateSignValue([], '', 'nonce', 'timestamp'))
        ->toThrow(InvalidArgumentException::class, 'API key and secret must be configured');
});

it('handles empty query parameters correctly', function () {
    $result = Header::digestQueryParameters([]);
    
    expect($result)->toBe('');
});

it('handles empty body correctly', function () {
    $nonce = '123456';
    $timestamp = '20241120123045';
    
    $sign = Header::generateSignValue([], '', $nonce, $timestamp);
    
    expect($sign)
        ->toBeString()
        ->toHaveLength(64);
});

it('generates consistent signature for same inputs', function () {
    $nonce = '123456';
    $timestamp = '20241120123045';
    $queryParams = ['id' => '1'];
    $body = '{"test":"value"}';
    
    $sign1 = Header::generateSignValue($queryParams, $body, $nonce, $timestamp);
    $sign2 = Header::generateSignValue($queryParams, $body, $nonce, $timestamp);
    
    expect($sign1)->toBe($sign2);
});

it('generates different signatures for different inputs', function () {
    $nonce = '123456';
    $timestamp = '20241120123045';
    
    $sign1 = Header::generateSignValue(['id' => '1'], '{"test":"value1"}', $nonce, $timestamp);
    $sign2 = Header::generateSignValue(['id' => '2'], '{"test":"value2"}', $nonce, $timestamp);
    
    expect($sign1)->not->toBe($sign2);
});
