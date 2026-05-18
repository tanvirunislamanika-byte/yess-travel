<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'widget_id',
        'locale',
        'title',
        'description'
    ];

    /**
     * Get the widget that owns the translation.
     */
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}