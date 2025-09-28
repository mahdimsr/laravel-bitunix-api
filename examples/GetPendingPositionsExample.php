<?php

/**
 * Example usage of Get Pending Positions functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to get pending positions from Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\GetPendingPositionsRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(GetPendingPositionsRequestContract::class);

    echo "📊 Get Pending Positions Examples\n\n";

    // Example 1: Get all pending positions
    echo "1. Getting all pending positions...\n";
    $response = $api->getPendingPositions();

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Pending positions retrieved successfully!\n";
            echo "Number of positions: " . count($data['data']) . "\n";
            
            foreach ($data['data'] as $position) {
                echo "  - Position ID: " . $position['positionId'] . "\n";
                echo "    Symbol: " . $position['symbol'] . "\n";
                echo "    Side: " . $position['side'] . "\n";
                echo "    Quantity: " . $position['qty'] . "\n";
                echo "    Unrealized PnL: " . $position['unrealizedPNL'] . "\n";
                echo "    Margin: " . $position['margin'] . "\n";
                echo "    Leverage: " . $position['leverage'] . "\n";
                echo "    ---\n";
            }
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 2: Get pending positions by symbol
    echo "2. Getting pending positions for BTCUSDT...\n";
    $response = $api->getPendingPositions('BTCUSDT');

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ BTCUSDT pending positions retrieved successfully!\n";
            echo "Number of BTCUSDT positions: " . count($data['data']) . "\n";
            
            foreach ($data['data'] as $position) {
                echo "  - Position ID: " . $position['positionId'] . "\n";
                echo "    Entry Value: " . $position['entryValue'] . "\n";
                echo "    Average Open Price: " . $position['avgOpenPrice'] . "\n";
                echo "    Liquidation Price: " . $position['liqPrice'] . "\n";
                echo "    Margin Rate: " . $position['marginRate'] . "\n";
                echo "    ---\n";
            }
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 3: Get specific position by ID
    echo "3. Getting specific position by ID...\n";
    $positionId = '19848247723672';
    $response = $api->getPendingPositions(null, $positionId);

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position {$positionId} retrieved successfully!\n";
            
            if (!empty($data['data'])) {
                $position = $data['data'][0];
                echo "  Position Details:\n";
                echo "    Position ID: " . $position['positionId'] . "\n";
                echo "    Symbol: " . $position['symbol'] . "\n";
                echo "    Side: " . $position['side'] . "\n";
                echo "    Quantity: " . $position['qty'] . "\n";
                echo "    Entry Value: " . $position['entryValue'] . "\n";
                echo "    Average Open Price: " . $position['avgOpenPrice'] . "\n";
                echo "    Unrealized PnL: " . $position['unrealizedPNL'] . "\n";
                echo "    Realized PnL: " . $position['realizedPNL'] . "\n";
                echo "    Margin: " . $position['margin'] . "\n";
                echo "    Leverage: " . $position['leverage'] . "\n";
                echo "    Margin Mode: " . $position['marginMode'] . "\n";
                echo "    Position Mode: " . $position['positionMode'] . "\n";
                echo "    Liquidation Price: " . $position['liqPrice'] . "\n";
                echo "    Margin Rate: " . $position['marginRate'] . "\n";
                echo "    Fee: " . $position['fee'] . "\n";
                echo "    Funding: " . $position['funding'] . "\n";
                echo "    Created: " . date('Y-m-d H:i:s', $position['ctime'] / 1000) . "\n";
                echo "    Modified: " . date('Y-m-d H:i:s', $position['mtime'] / 1000) . "\n";
            } else {
                echo "No position found with ID: {$positionId}\n";
            }
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 4: Get positions with both symbol and position ID
    echo "4. Getting positions with both symbol and position ID...\n";
    $response = $api->getPendingPositions('BTCUSDT', '19848247723672');

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Filtered positions retrieved successfully!\n";
            echo "Number of filtered positions: " . count($data['data']) . "\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Get Pending Positions Features:
 * 
 * - Get all pending positions
 * - Filter by trading pair (symbol)
 * - Filter by position ID
 * - Get detailed position information
 * - Rate limit: 10 req/sec/uid
 * 
 * Response includes:
 * - positionId: Position ID
 * - symbol: Trading pair
 * - qty: Position amount
 * - entryValue: Available amount for positions
 * - side: LONG or SHORT
 * - marginMode: ISOLATION or CROSS
 * - positionMode: ONE_WAY or HEDGE
 * - leverage: Leverage value
 * - fee: Transaction fees
 * - funding: Total funding fee
 * - realizedPNL: Realized PnL
 * - margin: Locked asset
 * - unrealizedPNL: Unrealized PnL
 * - liqPrice: Liquidation price
 * - marginRate: Margin ratio
 * - avgOpenPrice: Average open price
 * - ctime: Create timestamp
 * - mtime: Latest modify timestamp
 * 
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
