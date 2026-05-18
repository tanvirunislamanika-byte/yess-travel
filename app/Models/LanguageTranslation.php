<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'group_name',
        'key_name',
        'value'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
