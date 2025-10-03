<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function symbol($countryCode = null)
    {
        $symbols = [
            'PH' => '₱',  // Philippines
            'US' => '$',  // United States
            'EU' => '€',  // Europe
            'JP' => '¥',  // Japan
            'GB' => '£',  // UK
        ];

        // default to peso
        $countryCode = $countryCode ?? geoip()->getLocation()->iso_code ?? 'PH';

        return $symbols[$countryCode] ?? '₱';
    }
}
