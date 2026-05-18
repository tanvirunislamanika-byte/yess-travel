<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
    protected $table = 'countries';
    
    protected $fillable = [
        'name',
        'iso3',
        'iso2',
        'numeric_code',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'subregion',
        'latitude',
        'longitude',
        'emoji',
        'emojiU'
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all translations for this country
     */
    public function translations()
    {
        return $this->morphMany(ContentTranslation::class, 'translatable');
    }

    /**
     * Get translated field value
     */
    public function getTranslatedField($field, $locale = null)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        if ($locale === 'en') {
            return $this->$field ?? '';
        }

        $translation = $this->translations()
            ->where('locale', $locale)
            ->where('field_name', $field)
            ->first();

        return $translation ? $translation->field_value : ($this->$field ?? '');
    }

    /**
     * Get translated name
     */
    public function getTranslatedName($locale = null)
    {
        return $this->getTranslatedField('name', $locale);
    }
}
