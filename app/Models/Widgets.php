<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widgets extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'content',
        'position',
        'is_active',
        'sort_order'
    ];

    public function widget_data()
    {
        return $this->hasOne('App\Models\WidgetsData','widget_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Models\WidgetPages','widget_page_id');
    }

    /**
     * Get the translations for the widget.
     */
    public function translations()
    {
        return $this->hasMany(WidgetTranslation::class, 'widget_id');
    }

    /**
     * Get the translated title for the widget.
     */
    public function getTranslatedTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()
            ->where('locale', $locale)
            ->first();
            
        return $translation ? $translation->title : $this->title;
    }

    /**
     * Get the translated description for the widget.
     */
    public function getTranslatedDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        
        $translation = $this->translations()
            ->where('locale', $locale)
            ->first();
            
        return $translation ? $translation->description : $this->description;
    }

    /**
     * Set translation for a specific locale.
     */
    public function setTranslation($locale, $field, $value)
    {
        return $this->translations()->updateOrCreate(
            ['locale' => $locale],
            [$field => $value]
        );
    }
}
