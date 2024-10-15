<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends User
{
    //parent Model
    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone',
        'whatsapp',
        'family_members'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function address()
    {
        return $this->hasOne(Address::class,'guardian_id','user_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'guardian_id','user_id');
    }
}
