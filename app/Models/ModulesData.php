<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulesData extends Model
{
    protected $table = 'modules_data';

    protected $fillable = [
        'title', 'description', 'category', 'sub_category', 'module_id', 
        'meta_title', 'meta_keywords', 'meta_description', 'image', 'images',
        'extra_field_1', 'extra_field_2', 'extra_field_3', 'extra_field_4', 'extra_field_5',
        'extra_field_6', 'extra_field_7', 'extra_field_8', 'extra_field_9', 'extra_field_10',
        'extra_field_11', 'extra_field_12', 'extra_field_13', 'extra_field_14', 'extra_field_15',
        'extra_field_16', 'extra_field_17', 'extra_field_18', 'extra_field_19', 'extra_field_20',
        'extra_field_21', 'extra_field_22', 'extra_field_23', 'extra_field_24', 'extra_field_25',
        'extra_field_26', 'extra_field_27', 'extra_field_28', 'extra_field_29', 'extra_field_30',
        'extra_field_31', 'extra_field_32', 'extra_field_33', 'extra_field_34', 'extra_field_35',
        'extra_field_36', 'extra_field_37', 'extra_field_38', 'extra_field_39', 'extra_field_40',
        'extra_field_41', 'extra_field_42', 'extra_field_43', 'extra_field_44', 'extra_field_45',
        'extra_field_46', 'extra_field_47', 'extra_field_48', 'extra_field_49', 'extra_field_50'
    ];

    public function results()
    {
        return $this->hasMany('App\Models\ModulesData','category');
    }

    public function count()
    {
        return $this->results()->count();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'hotel_id');
    }

    // Tour-specific relationships
    public function departureCountry()
    {
        return $this->belongsTo(Country::class, 'extra_field_5');
    }

    public function departureState()
    {
        return $this->belongsTo(State::class, 'extra_field_6');
    }

    public function departureCity()
    {
        return $this->belongsTo(City::class, 'extra_field_7');
    }

    public function tourType()
    {
        return $this->belongsTo(ModulesData::class, 'extra_field_10')->where('module_id', 35);
    }

    public function transportType()
    {
        return $this->belongsTo(ModulesData::class, 'extra_field_11')->where('module_id', 36);
    }

    /**
     * Get all translations for this content
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
     * Get translated title
     */
    public function getTranslatedTitle($locale = null)
    {
        return $this->getTranslatedField('title', $locale);
    }

    /**
     * Get translated description
     */
    public function getTranslatedDescription($locale = null)
    {
        return $this->getTranslatedField('description', $locale);
    }

    /**
     * Get translated meta title
     */
    public function getTranslatedMetaTitle($locale = null)
    {
        return $this->getTranslatedField('meta_title', $locale);
    }

    /**
     * Get translated meta description
     */
    public function getTranslatedMetaDescription($locale = null)
    {
        return $this->getTranslatedField('meta_description', $locale);
    }

    /**
     * Get translated meta keywords
     */
    public function getTranslatedMetaKeywords($locale = null)
    {
        return $this->getTranslatedField('meta_keywords', $locale);
    }

    /**
     * Get translated extra field
     */
    public function getTranslatedExtraField($fieldNumber, $locale = null)
    {
        return $this->getTranslatedField("extra_field_{$fieldNumber}", $locale);
    }

    /**
     * Store or update translation
     */
    public function setTranslation($field, $locale, $value)
    {
        return $this->translations()->updateOrCreate(
            [
                'locale' => $locale,
                'field_name' => $field
            ],
            [
                'field_value' => $value
            ]
        );
    }

}
