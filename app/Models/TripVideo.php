<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripVideo extends Model
{
    use HasFactory;
    protected $table = 'trip_videos';

    protected $fillable = [
        'trip_id',
        'link',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
