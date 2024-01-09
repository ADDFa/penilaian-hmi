<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function score()
    {
        return $this->hasOne(Score::class);
    }
}
