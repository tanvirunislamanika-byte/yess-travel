<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag',
        'flag_image',
        'is_active',
        'is_rtl',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_rtl' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function translations()
    {
        return $this->hasMany(LanguageTranslation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
