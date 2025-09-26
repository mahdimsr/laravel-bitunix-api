<?php

use Msr\LaravelBitunixApi\Requests\Header;

it('sort query parameters', function () {

    $emptyQuery = Header::sortQueryParameters([]);
    expect($emptyQuery)->toBeEmpty();

    $params = [
        'z_index' => 'z value',
        'a_index' => 'a value',
        'b_index' => 'b value',
    ];
    $sortedParams = Header::sortQueryParameters($params);
    expect($sortedParams)
        ->toMatchArray([
            'a_index' => 'a value',
            'b_index' => 'b value',
            'z_index' => 'z value',
        ]);
});

it('get sorted query params as string value', function () {

    $digestedParam = Header::digestQueryParameters([]);
    expect($digestedParam)->toBeNull();

    $params = [
        'z_index' => 'z_value',
        'a_index' => 'a_value',
        'b_index' => 'b_value',
    ];
    $sortedParams = Header::digestQueryParameters($params);
    expect($sortedParams)->toEqual('a_indexa_valueb_indexb_valuez_indexz_value');
});

it('get some random string 32 bit', function () {

    $firstRandom = Header::generateNonce();
    $secondRandom = Header::generateNonce();

    expect($firstRandom)
        ->toBeString()
        ->toHaveLength(32)
        ->not()->toEqual($secondRandom)
        ->not()->toBeNull();
});
