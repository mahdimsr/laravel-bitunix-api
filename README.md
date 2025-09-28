# Laravel Bitunix API Package

A Laravel package for interacting with the Bitunix cryptocurrency exchange API.

## Installation

```bash
composer require msr/laravel-bitunix-api
```

## Configuration

### 1. Environment Variables

Add the following variables to your `.env` file:

```env
BITUNIX_API_KEY=your-api-key-here
BITUNIX_API_SECRET=your-api-secret-here
BITUNIX_LANGUAGE=en-US
```

### 2. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=bitunix-api-config
```

### 3. Verify Configuration

Run the configuration check script:

```bash
php scripts/check-config.php
```

## Usage

### Change Leverage

```php
use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;

$api = app(ChangeLeverageRequestContract::class);
$response = $api->changeLeverage('BTCUSDT', 'USDT', 12);

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getBody()->getContents(), true);
    if ($data['code'] === 0) {
        echo "Leverage changed successfully!";
    }
}
```

### Change Margin Mode

```php
use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;

$api = app(ChangeMarginModeRequestContract::class);
$response = $api->changeMarginMode('BTCUSDT', 'USDT', 'ISOLATION');

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getBody()->getContents(), true);
    if ($data['code'] === 0) {
        echo "Margin mode changed successfully!";
    }
}
```

### Place Order

```php
use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;

$api = app(PlaceOrderRequestContract::class);

// Basic market order
$response = $api->placeOrder('BTCUSDT', '0.1', 'BUY', 'OPEN', 'MARKET');

// Limit order with take profit and stop loss
$response = $api->placeOrder(
    'BTCUSDT',
    '0.1',
    'BUY',
    'OPEN',
    'LIMIT',
    '50000',        // price
    null,           // positionId
    'GTC',          // effect
    'order-123',   // clientId
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
        echo "Order placed successfully!";
        echo "Order ID: " . $data['data']['orderId'];
    }
}
```

### Get Future Kline Data

```php
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;

$api = app(FutureKLineRequestContract::class);
$response = $api->getFutureKline('BTCUSDT', '1h', 100);

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getBody()->getContents(), true);
    // Process kline data
}
```

## API Methods

### Account Management

- `changeLeverage(string $symbol, string $marginCoin, int $leverage)` - Change leverage
- `changeMarginMode(string $symbol, string $marginCoin, string $marginMode)` - Change margin mode

### Trading

- `placeOrder(...)` - Place a new order with full support for all order types, take profit, stop loss, and position management

### Market Data

- `getFutureKline(string $symbol, string $interval, int $limit, ?int $startTime, ?int $endTime, string $type)` - Get kline data

## Configuration Options

| Option | Description | Default |
|--------|-------------|---------|
| `future_base_uri` | Bitunix API base URI | `https://fapi.bitunix.com/` |
| `api_key` | Your API key | From `BITUNIX_API_KEY` env var |
| `api_secret` | Your API secret | From `BITUNIX_API_SECRET` env var |
| `language` | API language | From `BITUNIX_LANGUAGE` env var or `en-US` |

## Rate Limits

- **Change Leverage**: 10 req/sec/uid
- **Change Margin Mode**: 10 req/sec/uid
- **Place Order**: 10 req/sec/uid

## Error Handling

All methods return a `ResponseInterface` object. Check the response status and parse the JSON response:

```php
$response = $api->changeLeverage('BTCUSDT', 'USDT', 12);

if ($response->getStatusCode() === 200) {
    $data = json_decode($response->getBody()->getContents(), true);
    
    if ($data['code'] === 0) {
        // Success
        echo "Operation successful: " . $data['msg'];
    } else {
        // API Error
        echo "API Error: " . $data['msg'];
    }
} else {
    // HTTP Error
    echo "HTTP Error: " . $response->getStatusCode();
}
```

## Testing

Run the test suite:

```bash
vendor/bin/pest
```

Run specific tests:

```bash
vendor/bin/pest tests/ChangeLeverageTest.php
vendor/bin/pest tests/ChangeMarginModeTest.php
vendor/bin/pest tests/PlaceOrderTest.php
vendor/bin/pest tests/HeaderTest.php
```

## Examples

See the `examples/` directory for complete usage examples:

- `ChangeLeverageExample.php`
- `ChangeMarginModeExample.php`
- `PlaceOrderExample.php`

## Troubleshooting

### API credentials not loading from .env

1. Make sure your `.env` file is in the project root
2. Check that the variable names match exactly (case-sensitive)
3. Restart your application/server after changing .env
4. Clear config cache: `php artisan config:clear`

### Signature generation fails

1. Verify API key and secret are correct
2. Check that the credentials have the necessary permissions
3. Ensure your system time is synchronized

## Security

- Never commit API credentials to version control
- Use environment variables for all sensitive data
- Restrict API key permissions to minimum required
- Regularly rotate API keys

## License

MIT License. See LICENSE file for details.

## Support

For issues and questions, please create an issue on the GitHub repository.