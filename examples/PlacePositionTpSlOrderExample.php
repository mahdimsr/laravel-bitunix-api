<?php

/**
 * Example usage of Place Position TP/SL Order functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to place position TP/SL orders on Bitunix exchange.
 * 
 * Note: When triggered, it will close the position at market price based on the position quantity at that time.
 * Each position can only have one Position TP/SL Order.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\PlacePositionTpSlOrderRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(PlacePositionTpSlOrderRequestContract::class);

    echo "🎯 Place Position TP/SL Order Examples\n\n";

    // Example 1: Place position TP/SL order with both take profit and stop loss
    echo "1. Placing position TP/SL order with both TP and SL...\n";
    $response = $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'LAST_PRICE',   // tpStopType
        '45000',        // slPrice
        'LAST_PRICE'    // slStopType
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position TP/SL order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 2: Place position TP/SL order with take profit only
    echo "2. Placing position TP/SL order with take profit only...\n";
    $response = $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        '50000',        // tpPrice
        'MARK_PRICE'    // tpStopType
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position TP order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 3: Place position TP/SL order with stop loss only
    echo "3. Placing position TP/SL order with stop loss only...\n";
    $response = $api->placePositionTpSlOrder(
        'BTCUSDT',
        '111',
        null,           // tpPrice
        null,           // tpStopType
        '45000',        // slPrice
        'MARK_PRICE'    // slStopType
    );

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position SL order placed successfully!\n";
            echo 'Order ID: '.$data['data']['orderId']."\n";
        } else {
            echo '❌ API Error: '.$data['msg']."\n";
        }
    } else {
        echo '❌ HTTP Error: '.$response->getStatusCode()."\n";
    }

    echo "\n";

    // Example 4: Place position TP/SL order with different symbols
    echo "4. Placing position TP/SL orders for different symbols...\n";
    $symbols = ['BTCUSDT', 'ETHUSDT', 'ADAUSDT'];

    foreach ($symbols as $symbol) {
        echo "Placing position TP/SL order for {$symbol}...\n";
        
        $response = $api->placePositionTpSlOrder(
            $symbol,
            '111',
            '50000',
            'LAST_PRICE',
            '45000',
            'LAST_PRICE'
        );
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                echo "✅ {$symbol} position TP/SL order placed successfully!\n";
                echo 'Order ID: '.$data['data']['orderId']."\n";
            } else {
                echo "❌ Failed to place {$symbol} position TP/SL order: ".$data['msg']."\n";
            }
        } else {
            echo "❌ HTTP Error for {$symbol}: ".$response->getStatusCode()."\n";
        }
    }

    echo "\n";

    // Example 5: Place position TP/SL order with different position IDs
    echo "5. Placing position TP/SL orders for different position IDs...\n";
    $positionIds = ['111', '222', '333'];

    foreach ($positionIds as $positionId) {
        echo "Placing position TP/SL order for position ID {$positionId}...\n";
        
        $response = $api->placePositionTpSlOrder(
            'BTCUSDT',
            $positionId,
            '50000',
            'LAST_PRICE',
            '45000',
            'LAST_PRICE'
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

    // Example 6: Place position TP/SL order with different stop types
    echo "6. Placing position TP/SL orders with different stop types...\n";
    $stopTypeCombinations = [
        ['LAST_PRICE', 'LAST_PRICE'],
        ['MARK_PRICE', 'MARK_PRICE'],
        ['LAST_PRICE', 'MARK_PRICE'],
        ['MARK_PRICE', 'LAST_PRICE'],
    ];

    foreach ($stopTypeCombinations as $index => $combination) {
        echo "Placing position TP/SL order with stop types: {$combination[0]}, {$combination[1]}...\n";
        
        $response = $api->placePositionTpSlOrder(
            'BTCUSDT',
            '111',
            '50000',
            $combination[0],
            '45000',
            $combination[1]
        );
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                echo "✅ Position TP/SL order with {$combination[0]}/{$combination[1]} placed successfully!\n";
                echo 'Order ID: '.$data['data']['orderId']."\n";
            } else {
                echo "❌ Failed to place position TP/SL order: ".$data['msg']."\n";
            }
        } else {
            echo "❌ HTTP Error: ".$response->getStatusCode()."\n";
        }
    }

    echo "\n";

    // Example 7: Error handling
    echo "7. Error handling example...\n";
    $response = $api->placePositionTpSlOrder('INVALID', '111');
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position TP/SL order placed successfully!\n";
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
 * Place Position TP/SL Order Features:
 * 
 * - Place position TP/SL orders for existing positions
 * - Rate limit: 10 req/sec/UID
 * - Supports both take profit and stop loss
 * - When triggered, closes position at market price
 * - Each position can only have one Position TP/SL Order
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
 * 
 * Note: At least one of tpPrice or slPrice is required.
 * 
 * Key Differences from regular TP/SL Order:
 * - Simpler parameters (no order types, prices, quantities)
 * - Automatically closes position at market price when triggered
 * - One order per position limit
 * - Uses position/place_order endpoint
 * 
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
