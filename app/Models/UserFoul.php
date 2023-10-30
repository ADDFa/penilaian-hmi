<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFoul extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function foul()
    {
        return $this->belongsTo(Foul::class);
    }
}
