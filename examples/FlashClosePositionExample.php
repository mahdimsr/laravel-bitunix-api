<?php

/**
 * Example usage of Flash Close Position functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to flash close positions on Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\FlashClosePositionRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(FlashClosePositionRequestContract::class);

    echo "⚡ Flash Close Position Examples\n\n";

    // Example 1: Flash close a single position
    echo "1. Flash closing position...\n";
    $positionId = '19848247723672';
    
    $response = $api->flashClosePosition($positionId);

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position flash closed successfully!\n";
            echo "Position ID: " . $data['data']['positionId'] . "\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 2: Flash close multiple positions
    echo "2. Flash closing multiple positions...\n";
    $positionIds = [
        '19848247723672',
        '19848247723673',
        '19848247723674'
    ];

    foreach ($positionIds as $positionId) {
        echo "Closing position: {$positionId}...\n";
        
        $response = $api->flashClosePosition($positionId);
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                echo "✅ Position {$positionId} closed successfully!\n";
            } else {
                echo "❌ Failed to close position {$positionId}: " . $data['msg'] . "\n";
            }
        } else {
            echo "❌ HTTP Error for position {$positionId}: " . $response->getStatusCode() . "\n";
        }
    }

    echo "\n";

    // Example 3: Error handling
    echo "3. Error handling example...\n";
    $invalidPositionId = 'invalid-position-id';
    
    $response = $api->flashClosePosition($invalidPositionId);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Position closed successfully!\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
            echo "This is expected for invalid position ID\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Flash Close Position Features:
 * 
 * - Closes position by position ID
 * - Rate limit: 5 req/sec/uid
 * - Immediate position closure
 * - No additional parameters required
 * 
 * Important Notes:
 * 
 * - Position ID must be valid and exist
 * - Position must be open to be closed
 * - This is an immediate action (flash close)
 * - Use with caution as it closes positions immediately
 * 
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
