<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfectiveIndicator extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(AfectiveIndicatorCategory::class);
    }
}
