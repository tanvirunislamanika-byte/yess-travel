<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'locale',
        'field_name',
        'field_value'
    ];

    /**
     * Get the parent translatable model
     */
    public function translatable()
    {
        return $this->morphTo();
    }

    /**
     * Scope for specific locale
     */
    public function scopeForLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope for specific field
     */
    public function scopeForField($query, $field)
    {
        return $query->where('field_name', $field);
    }
}
