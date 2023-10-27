<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function middletest()
    {
        return $this->hasMany(MiddleTest::class);
    }

    public function liveliness()
    {
        return $this->hasMany(Livelines::class);
    }
}
