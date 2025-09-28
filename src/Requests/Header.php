<?php

namespace Msr\LaravelBitunixApi\Requests;

class Header
{
    /**
     * Sort query parameters in ascending ASCII order by Key
     */
    public static function sortQueryParameters(array $parameters): array
    {
        if (empty($parameters)) {
            return [];
        }

        ksort($parameters, SORT_STRING);

        return $parameters;
    }

    /**
     * Convert sorted parameters to string format
     * Example: ["id" => "1", "uid" => "200"] becomes "id1uid200"
     * According to Bitunix documentation: String queryParams = "id1uid200"
     */
    public static function digestQueryParameters(array $parameters): string
    {
        if (empty($parameters)) {
            return '';
        }

        $sortedParameters = self::sortQueryParameters($parameters);
        $result = '';

        foreach ($sortedParameters as $key => $value) {
            $result .= $key . $value;
        }

        return $result;
    }

    /**
     * Generate a random 32-bit nonce string
     */
    public static function generateNonce(): string
    {
        return bin2hex(random_bytes(16)); // 32 characters
    }

    /**
     * Generate current timestamp in milliseconds
     */
    public static function generateTimestamp(): string
    {
        return (string) round(microtime(true) * 1000);
    }

    /**
     * Generate signature according to Bitunix API documentation
     *
     * Steps:
     * 1. Sort all queryParams in ascending ASCII order by Key
     * 2. Remove all spaces from body string
     * 3. Create digest: SHA256(nonce + timestamp + api-key + queryParams + body)
     * 4. Create sign: SHA256(digest + secretKey)
     */
    public static function generateSignValue(array $queryParams = [], string $body = '', string $nonce = '', string $timestamp = ''): string
    {
        $apiKey = config('bitunix-api.api_key');
        $apiSecret = config('bitunix-api.api_secret');

        if (empty($apiKey) || empty($apiSecret)) {
            throw new \InvalidArgumentException('API key and secret must be configured');
        }

        // Step 1: Sort query parameters in ascending ASCII order
        $queryParamsString = self::digestQueryParameters($queryParams);

        // Step 2: Remove all spaces from body (already done if JSON encoded properly)
        $bodyString = trim($body);

        // Step 3: Create digest: SHA256(nonce + timestamp + api-key + queryParams + body (if not empty))
        if (strlen($bodyString) == 0) {
            $digestInput = $nonce.$timestamp.$apiKey.$queryParamsString;
        }else{
            $digestInput = $nonce.$timestamp.$apiKey.$queryParamsString.$bodyString;
        }
        $digest = hash('sha256', $digestInput);

        // Step 4: Create sign: SHA256(digest + secretKey)
        $signInput = $digest.$apiSecret;
        $sign = hash('sha256', $signInput);

        return $sign;
    }

    /**
     * Generate complete headers for authenticated requests
     */
    public static function generateHeaders(array $queryParams = [], string $body = ''): array
    {
        $nonce = self::generateNonce();
        $timestamp = self::generateTimestamp();
        $sign = self::generateSignValue($queryParams, $body, $nonce, $timestamp);

        return [
            'api-key' => config('bitunix-api.api_key'),
            'sign' => $sign,
            'nonce' => $nonce,
            'timestamp' => $timestamp,
            'language' => config('bitunix-api.language', 'en-US'),
            'Content-Type' => 'application/json',
        ];
    }
}
