<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigned_contacts extends Model
{
    use HasFactory;

    public $table = 'assigned_contacts';

    public function member($value='')
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
