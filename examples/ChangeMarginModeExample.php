<?php

/**
 * Example usage of Change Margin Mode functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to change margin mode for a trading pair on Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(ChangeMarginModeRequestContract::class);

    // Change margin mode for BTCUSDT pair
    $symbol = 'BTCUSDT';
    $marginCoin = 'USDT';
    $marginMode = 'ISOLATION'; // or 'CROSS'

    echo "Changing margin mode for {$symbol} to {$marginMode}...\n";

    $response = $api->changeMarginMode($symbol, $marginCoin, $marginMode);

    // Check response status
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['code'] === 0) {
            echo "✅ Margin mode changed successfully!\n";
            echo 'Symbol: '.$data['data'][0]['symbol']."\n";
            echo 'Margin Coin: '.$data['data'][0]['marginCoin']."\n";
            echo 'Margin Mode: '.$data['data'][0]['marginMode']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Available Margin Modes:
 * - ISOLATION: Isolated margin mode
 * - CROSS: Cross margin mode
 *
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
