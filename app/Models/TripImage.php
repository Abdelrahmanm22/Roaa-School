<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripImage extends Model
{
    use HasFactory;
    protected $table = 'trip_images';

    protected $fillable = [
        'trip_id',
        'image',
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
