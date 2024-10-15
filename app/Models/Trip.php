<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'subtitle',
        'date',
        'day',
        'description',
        'image',
    ];


    // Filters trips where the date is greater than today
    public function scopeNextTrips(Builder $builder)
    {
        return $builder->where('date', '>=', Carbon::now()->format('Y-m-d'))->orderBy('date', 'asc')->select('id','title', 'date', 'day');
    }


    // Filters to get only the next upcoming trip
    public function scopeNextTrip(Builder $builder)
    {
        return $builder
            ->where('date', '>', Carbon::now()->format('Y-m-d')) // Get trips in the future
            ->orderBy('date', 'asc') // Order by the closest date first
            ->select('id','title', 'date', 'day')
            ->first(); // Get only the first (next) trip
    }
    protected $hidden = [
        'created_at',  // Hide created_at
        'updated_at',  // Hide updated_at
    ];
}
