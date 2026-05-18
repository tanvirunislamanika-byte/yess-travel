<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    public $table = 'history';


    public function member($value='')
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
