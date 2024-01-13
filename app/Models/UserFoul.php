<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFoul extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function afectiveIndidator()
    {
        return $this->belongsTo(AfectiveIndicator::class, "afective_indicator_id");
    }
}
