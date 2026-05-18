<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';
    
    protected $fillable = [
        'name',
        'country_id',
        'state_code',
        'type',
        'latitude',
        'longitude'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all translations for this state
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
