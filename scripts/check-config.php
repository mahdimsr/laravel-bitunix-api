<?php

/**
 * Configuration Check Script
 *
 * This script helps you verify that your Bitunix API configuration is working correctly.
 */

require_once __DIR__.'/../vendor/autoload.php';

use Msr\LaravelBitunixApi\Requests\Header;

echo "🔍 Checking Bitunix API Configuration...\n\n";

// Check if .env file exists
$envFile = __DIR__.'/../.env';
if (! file_exists($envFile)) {
    echo "❌ .env file not found. Please create one based on .env.example\n";
    exit(1);
}

// Load environment variables
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            putenv(trim($key).'='.trim($value));
        }
    }
}

// Check environment variables
$apiKey = getenv('BITUNIX_API_KEY');
$apiSecret = getenv('BITUNIX_API_SECRET');
$language = getenv('BITUNIX_LANGUAGE') ?: 'en-US';

echo "📋 Environment Variables:\n";
echo '  BITUNIX_API_KEY: '.(empty($apiKey) ? '❌ Not set' : '✅ Set ('.substr($apiKey, 0, 8).'...)')."\n";
echo '  BITUNIX_API_SECRET: '.(empty($apiSecret) ? '❌ Not set' : '✅ Set ('.substr($apiSecret, 0, 8).'...)')."\n";
echo '  BITUNIX_LANGUAGE: '.($language)."\n\n";

if (empty($apiKey) || empty($apiSecret)) {
    echo "❌ API credentials not configured properly.\n";
    echo "Please set BITUNIX_API_KEY and BITUNIX_API_SECRET in your .env file.\n";
    exit(1);
}

// Test configuration loading
config([
    'bitunix-api.future_base_uri' => 'https://fapi.bitunix.com/',
    'bitunix-api.api_key' => $apiKey,
    'bitunix-api.api_secret' => $apiSecret,
    'bitunix-api.language' => $language,
]);

echo "🔧 Configuration Test:\n";
echo '  Base URI: '.config('bitunix-api.future_base_uri')."\n";
echo '  API Key: '.substr(config('bitunix-api.api_key'), 0, 8)."...\n";
echo '  API Secret: '.substr(config('bitunix-api.api_secret'), 0, 8)."...\n";
echo '  Language: '.config('bitunix-api.language')."\n\n";

// Test header generation
try {
    echo "🔐 Testing Header Generation:\n";
    $headers = Header::generateHeaders([], '{"test":"value"}');

    echo '  API Key: '.$headers['api-key']."\n";
    echo '  Sign: '.substr($headers['sign'], 0, 16)."...\n";
    echo '  Nonce: '.$headers['nonce']."\n";
    echo '  Timestamp: '.$headers['timestamp']."\n";
    echo '  Language: '.$headers['language']."\n";
    echo '  Content-Type: '.$headers['Content-Type']."\n\n";

    echo "✅ Configuration is working correctly!\n";
    echo "You can now use the Bitunix API package in your application.\n";

} catch (Exception $e) {
    echo '❌ Error generating headers: '.$e->getMessage()."\n";
    exit(1);
}
