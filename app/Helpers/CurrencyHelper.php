<?php

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Get the default currency settings
     */
    public static function getDefaultCurrency()
    {
        return Setting::getDefaultCurrency();
    }

    /**
     * Format price with currency symbol
     */
    public static function formatPrice($amount, $showCurrency = true)
    {
        if ($amount === null || $amount === '') {
            return 'Price on request';
        }

        $currency = self::getDefaultCurrency();
        $formattedAmount = number_format($amount, 0);

        if ($showCurrency) {
            return $currency['symbol'] . ' ' . $formattedAmount;
        }

        return $formattedAmount;
    }

    /**
     * Get currency symbol only
     */
    public static function getSymbol()
    {
        $currency = self::getDefaultCurrency();
        return $currency['symbol'];
    }

    /**
     * Get currency code only
     */
    public static function getCode()
    {
        $currency = self::getDefaultCurrency();
        return $currency['code'];
    }

    /**
     * Get currency name only
     */
    public static function getName()
    {
        $currency = self::getDefaultCurrency();
        return $currency['name'];
    }
}