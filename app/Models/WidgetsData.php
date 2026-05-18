<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'widget_id',
        'title',
        'content',
        'image',
        'link',
        'is_active',
        'sort_order'
    ];

    /**
     * Get the widget that owns the data.
     */
    public function widget()
    {
        return $this->belongsTo(Widgets::class, 'widget_id');
    }

    /**
     * Get the translations for the widget data.
     */
    public function translations()
    {
        return $this->hasMany(WidgetDataTranslation::class, 'widget_data_id');
    }

    /**
     * Get the translated value for a specific field.
     */
    public function getTranslatedField($fieldName, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()
            ->where('locale', $locale)
            ->where('field_name', $fieldName)
            ->first();
            
        return $translation ? $translation->field_value : $this->$fieldName;
    }

    /**
     * Get the translated value for an extra field.
     */
    public function getTranslatedExtraField($fieldNumber, $locale = null)
    {
        $fieldName = 'extra_field_' . $fieldNumber;
        return $this->getTranslatedField($fieldName, $locale);
    }

    /**
     * Get the translated description.
     */
    public function getTranslatedDescription($locale = null)
    {
        return $this->getTranslatedField('description', $locale);
    }

    /**
     * Set translation for a specific locale and field.
     */
    public function setTranslation($locale, $fieldName, $value)
    {
        return $this->translations()->updateOrCreate(
            [
                'locale' => $locale,
                'field_name' => $fieldName
            ],
            [
                'field_value' => $value
            ]
        );
    }
}
