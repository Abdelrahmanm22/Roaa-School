<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
