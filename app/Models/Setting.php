<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $type = 'string', $description = null)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]);
        }
        
        return true;
    }

    /**
     * Get default currency settings
     */
    public static function getDefaultCurrency()
    {
        $currencyCode = self::getValue('default_currency', 'PKR');
        $currencySymbol = self::getValue('default_currency_symbol', 'Rs');
        $currencyName = self::getValue('default_currency_name', 'Pakistani Rupee');
        
        return [
            'code' => $currencyCode,
            'symbol' => $currencySymbol,
            'name' => $currencyName
        ];
    }
}
