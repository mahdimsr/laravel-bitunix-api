<?php

/**
 * Example usage of Change Leverage functionality
 * 
 * This example demonstrates how to use the LaravelBitunixApi package
 * to change leverage for a trading pair on Bitunix exchange.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(ChangeLeverageRequestContract::class);
    
    // Change leverage for BTCUSDT pair
    $symbol = 'BTCUSDT';
    $marginCoin = 'USDT';
    $leverage = 12;
    
    echo "Changing leverage for {$symbol} to {$leverage}x...\n";
    
    $response = $api->changeLeverage($symbol, $marginCoin, $leverage);
    
    // Check response status
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        
        if ($data['code'] === 0) {
            echo "✅ Leverage changed successfully!\n";
            echo "Symbol: " . $data['data'][0]['symbol'] . "\n";
            echo "Margin Coin: " . $data['data'][0]['marginCoin'] . "\n";
            echo "New Leverage: " . $data['data'][0]['leverage'] . "\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

/**
 * Environment Variables Required:
 * 
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
