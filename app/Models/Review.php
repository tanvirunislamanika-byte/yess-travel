<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['rating', 'reason', 'review', 'user_id', 'hotel_id'];

    public function hotel()
    {
        return $this->belongsTo(ModulesData::class, 'hotel_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
