<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'message',
        'phone',
        'resume',
        'title',
        'status',
    ];

    protected $hidden = [
        'updated_at',
    ];

    // Accessor for created_at to format the date
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
