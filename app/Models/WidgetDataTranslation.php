<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetDataTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'widget_data_id',
        'locale',
        'field_name',
        'field_value'
    ];

    /**
     * Get the widget data that owns the translation.
     */
    public function widgetData()
    {
        return $this->belongsTo(WidgetData::class);
    }

    /**
     * Scope for specific locale.
     */
    public function scopeForLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for specific field.
     */
    public function scopeForField($query, $fieldName)
    {
        return $query->where('field_name', $fieldName);
    }
}