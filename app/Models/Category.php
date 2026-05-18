<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';  // Specify the table name if it differs from the default
    protected $fillable = ['name', 'slug'];  // Fields you can mass-assign

    // If needed, you can also define relationships (e.g., for blogs)
    public function blogs()
    {
        return $this->hasMany(ModulesData::class);
    }
}
