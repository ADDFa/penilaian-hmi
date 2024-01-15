<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liveliness extends Model
{
    use HasFactory;

    protected $table = "liveliness";
    protected $guarded = ["id"];
    public $timestamps = false;
}
