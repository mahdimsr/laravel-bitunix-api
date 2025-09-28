<?php

/**
 * Example usage of Get Single Account functionality
 *
 * This example demonstrates how to use the LaravelBitunixApi package
 * to get account details from Bitunix exchange.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\GetSingleAccountRequestContract;

// Example configuration (in real usage, these would be in your .env file)
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => 'your-api-key-here',
    'bitunix-api.api_secret' => 'your-api-secret-here',
    'bitunix-api.language' => 'en-US',
]);

try {
    // Get the API instance
    $api = app(GetSingleAccountRequestContract::class);

    echo "💰 Get Single Account Examples\n\n";

    // Example 1: Get USDT account details
    echo "1. Getting USDT account details...\n";
    $response = $api->getSingleAccount('USDT');

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ USDT account details retrieved successfully!\n";
            
            $account = $data['data'][0];
            echo "Account Details:\n";
            echo "  Margin Coin: " . $account['marginCoin'] . "\n";
            echo "  Available: " . $account['available'] . "\n";
            echo "  Frozen: " . $account['frozen'] . "\n";
            echo "  Margin: " . $account['margin'] . "\n";
            echo "  Transfer: " . $account['transfer'] . "\n";
            echo "  Position Mode: " . $account['positionMode'] . "\n";
            echo "  Cross Unrealized PnL: " . $account['crossUnrealizedPNL'] . "\n";
            echo "  Isolation Unrealized PnL: " . $account['isolationUnrealizedPNL'] . "\n";
            echo "  Bonus: " . $account['bonus'] . "\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 2: Get BTC account details
    echo "2. Getting BTC account details...\n";
    $response = $api->getSingleAccount('BTC');

    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ BTC account details retrieved successfully!\n";
            
            $account = $data['data'][0];
            echo "Account Details:\n";
            echo "  Margin Coin: " . $account['marginCoin'] . "\n";
            echo "  Available: " . $account['available'] . "\n";
            echo "  Frozen: " . $account['frozen'] . "\n";
            echo "  Margin: " . $account['margin'] . "\n";
            echo "  Transfer: " . $account['transfer'] . "\n";
            echo "  Position Mode: " . $account['positionMode'] . "\n";
            echo "  Cross Unrealized PnL: " . $account['crossUnrealizedPNL'] . "\n";
            echo "  Isolation Unrealized PnL: " . $account['isolationUnrealizedPNL'] . "\n";
            echo "  Bonus: " . $account['bonus'] . "\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

    echo "\n";

    // Example 3: Get multiple account details
    echo "3. Getting multiple account details...\n";
    $marginCoins = ['USDT', 'BTC', 'ETH'];

    foreach ($marginCoins as $marginCoin) {
        echo "Getting {$marginCoin} account details...\n";
        
        $response = $api->getSingleAccount($marginCoin);
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['code'] === 0) {
                $account = $data['data'][0];
                echo "✅ {$marginCoin} account: Available={$account['available']}, Frozen={$account['frozen']}, Margin={$account['margin']}\n";
            } else {
                echo "❌ Failed to get {$marginCoin} account: " . $data['msg'] . "\n";
            }
        } else {
            echo "❌ HTTP Error for {$marginCoin}: " . $response->getStatusCode() . "\n";
        }
    }

    echo "\n";

    // Example 4: Error handling
    echo "4. Error handling example...\n";
    $invalidMarginCoin = 'INVALID';
    
    $response = $api->getSingleAccount($invalidMarginCoin);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
        if ($data['code'] === 0) {
            echo "✅ Account details retrieved successfully!\n";
        } else {
            echo "❌ API Error: " . $data['msg'] . "\n";
            echo "This is expected for invalid margin coin\n";
        }
    } else {
        echo "❌ HTTP Error: " . $response->getStatusCode() . "\n";
    }

} catch (Exception $e) {
    echo '❌ Exception: '.$e->getMessage()."\n";
}

/**
 * Get Single Account Features:
 * 
 * - Get account details for specific margin coin
 * - Rate limit: 10 req/sec/uid
 * - Returns comprehensive account information
 * - Supports multiple margin coins
 * 
 * Response includes:
 * - marginCoin: Margin Coin
 * - available: Available quantity in the account
 * - frozen: Locked quantity of orders
 * - margin: Locked quantity of positions
 * - transfer: Maximum transferable amount
 * - positionMode: Position mode (ONE_WAY or HEDGE)
 * - crossUnrealizedPNL: Unrealized PnL for cross positions
 * - isolationUnrealizedPNL: Unrealized PnL for isolation positions
 * - bonus: Futures Bonus
 * 
 * Environment Variables Required:
 *
 * BITUNIX_API_KEY=your-api-key
 * BITUNIX_API_SECRET=your-api-secret
 * BITUNIX_LANGUAGE=en-US
 */
