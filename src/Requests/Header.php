<?php

namespace Msr\LaravelBitunixApi\Requests;

class Header
{
    public static function sortQueryParameters(array $parameters): ?array
    {
        $sortableParameters = $parameters;

        if (!count($sortableParameters)) {
            return [];
        }

        ksort($sortableParameters, SORT_LOCALE_STRING);
        return $sortableParameters;
    }

    public static function digestQueryParameters(array $parameters): ?string
    {
        if (!count($parameters)) {
            return null;
        }

        $sortedParameters = self::sortQueryParameters($parameters);
        return implode('', array_map(
            fn($k, $v) => $k . $v,
            array_keys($sortedParameters),
            array_values($sortedParameters)
        ));
    }

    public static function generateNonce(): ?string
    {
        return md5(uniqid(mt_rand(), true));
    }
}
