<?php

/**
 * Example usage of Place TP/SL Order functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to place TP/SL orders on Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\PlaceTpSlOrderRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(PlaceTpSlOrderRequestContract::class);

    echo "🎯 Place TP/SL Order Examples\n\n";

    // Example 1: Place TP/SL order with both take profit and stop loss
    echo "1. Placing TP/SL order with both TP and SL...\n";
    $response = $api->placeTpSlOrder(
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
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ TP/SL order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 2: Place TP/SL order with take profit only
    echo "2. Placing TP/SL order with take profit only...\n";
    $response = $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'MARK_PRICE',   // tpStopType
        null,           // slPrice
        null,           // slStopType
        'MARKET',       // tpOrderType
        null,           // tpOrderPrice
        null,           // slOrderType
        null,           // slOrderPrice
        '1',            // tpQty
        null            // slQty
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ TP order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 3: Place TP/SL order with stop loss only
    echo "3. Placing TP/SL order with stop loss only...\n";
    $response = $api->placeTpSlOrder(
        'BTCUSDT',
        '111',
        null,           // tpPrice
        null,           // tpStopType
        '45000',        // slPrice
        'MARK_PRICE',   // slStopType
        null,           // tpOrderType
        null,           // tpOrderPrice
        'MARKET',       // slOrderType
        null,           // slOrderPrice
        null,           // tpQty
        '1'             // slQty
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ SL order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 4: Place TP/SL order with different symbols
    echo "4. Placing TP/SL orders for different symbols...\n";
    $symbols = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($symbols as $symbol) {
        echo "Placing TP/SL order for {$symbol}...\n";

        $response = $api->placeTpSlOrder(
            $symbol,
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
        );

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                echo "✅ {$symbol} TP/SL order placed successfully!\n";
                echo 'Order ID: '.$data['data']['orderId']."\n";
            } else {
                echo "❌ Failed to place {$symbol} TP/SL order: ".$data['msg']."\n";
            }
        } else {
            echo "❌ HTTP Error for {$symbol}: ".$response->getStatusCode()."\n";
        }
    }

    echo "\n";

    // Example 5: Place TP/SL order with different position IDs
    echo "5. Placing TP/SL orders for different position IDs...\n";
    $positionIds = ['111', '222', '333'];

    foreach ($positionIds as $positionId) {
        echo "Placing TP/SL order for position ID {$positionId}...\n";

        $response = $api->placeTpSlOrder(
            'BTCUSDT',
            $positionId,
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
        );

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                echo "✅ Position {$positionId} TP/SL order placed successfully!\n";
                echo 'Order ID: '.$data['data']['orderId']."\n";
            } else {
                echo "❌ Failed to place position {$positionId} TP/SL order: ".$data['msg']."\n";
            }
        } else {
            echo "❌ HTTP Error for position {$positionId}: ".$response->getStatusCode()."\n";
        }
    }

    echo "\n";

    // Example 6: Error handling
    echo "6. Error handling example...\n";
    $response = $api->placeTpSlOrder('INVALID', '111');

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ TP/SL order placed successfully!\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
            echo "This is expected for invalid symbol\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Place TP/SL Order Features:
 *
 * - Place TP/SL orders for existing positions
 * - Rate limit: 10 req/sec/UID
 * - Supports both take profit and stop loss
 * - Flexible parameter configuration
 *
 * Required Parameters:
 * - symbol: Trading pair
 * - positionId: Position ID associated with TP/SL
 *
 * Optional Parameters:
 * - tpPrice: Take-profit trigger price
 * - tpStopType: Take-profit trigger type (LAST_PRICE/MARK_PRICE)
 * - slPrice: Stop-loss trigger price
 * - slStopType: Stop-loss trigger type (LAST_PRICE/MARK_PRICE)
 * - tpOrderType: Take-profit order type (LIMIT/MARKET)
 * - tpOrderPrice: Take-profit order price
 * - slOrderType: Stop-loss order type (LIMIT/MARKET)
 * - slOrderPrice: Stop-loss order price
 * - tpQty: Take-profit order quantity (base coin)
 * - slQty: Stop-loss order quantity (base coin)
 *
 * Note: At least one of tpPrice or slPrice is required.
 * At least one of tpQty or slQty is required.
 *
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
