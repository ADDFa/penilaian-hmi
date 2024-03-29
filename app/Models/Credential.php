<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    protected $guarded = [""];
    protected $hidden = ["password"];
    public $incrementing = false;
    public $timestamps = false;
}
