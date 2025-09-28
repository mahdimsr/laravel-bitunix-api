<?php

/**
 * Example usage of Place Order functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to place orders on Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(PlaceOrderRequestContract::class);

    echo "🚀 Placing Orders Examples\n\n";

    // Example 1: Basic Market Order
    echo "1. Placing a basic market order...\n";
    $response = $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'MARKET'
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Market order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    }

    echo "\n";

    // Example 2: Limit Order with Take Profit and Stop Loss
    echo "2. Placing a limit order with TP/SL...\n";
    $response = $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'BUY',
        'OPEN',
        'LIMIT',
        '50000',        // price
        null,           // positionId
        'GTC',          // effect
        'order-123',    // clientId
        false,          // reduceOnly
        '51000',        // tpPrice
        'MARK_PRICE',   // tpStopType
        'LIMIT',        // tpOrderType
        '51000.1',      // tpOrderPrice
        '49000',        // slPrice
        'MARK_PRICE',   // slStopType
        'LIMIT',        // slOrderType
        '49000.1'       // slOrderPrice
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Limit order with TP/SL placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
            echo 'Client ID: '.$data['data']['clientId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    }

    echo "\n";

    // Example 3: Close Position Order
    echo "3. Placing a close position order...\n";
    $response = $api->placeOrder(
        'BTCUSDT',
        '0.1',
        'SELL',
        'CLOSE',
        'MARKET',
        null,
        'position-123'  // positionId required for CLOSE
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Close position order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    }

    echo "\n";

    // Example 4: Reduce Only Order
    echo "4. Placing a reduce only order...\n";
    $response = $api->placeOrder(
        'BTCUSDT',
        '0.05',
        'SELL',
        'CLOSE',
        'MARKET',
        null,
        'position-123',
        null,
        null,
        true  // reduceOnly
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Reduce only order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Order Types:
 * - LIMIT: Limit orders (requires price)
 * - MARKET: Market orders
 *
 * Order Sides:
 * - BUY: Buy order
 * - SELL: Sell order
 *
 * Trade Sides:
 * - OPEN: Open a new position
 * - CLOSE: Close an existing position (requires positionId)
 *
 * Effect Types:
 * - IOC: Immediate or cancel
 * - FOK: Fill or kill
 * - GTC: Good till canceled (default)
 * - POST_ONLY: POST only
 *
 * Take Profit / Stop Loss Types:
 * - MARK_PRICE: Mark price
 * - LAST_PRICE: Last price
 *
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
