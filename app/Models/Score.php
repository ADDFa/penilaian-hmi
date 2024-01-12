<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function afectiveScore()
    {
        return $this->hasOne(AfectiveIndicatorScore::class);
    }

    public function middleTest()
    {
        return $this->hasMany(MiddleTest::class);
    }

    public function liveliness()
    {
        return $this->hasMany(Liveliness::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
