<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hourplanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour_one',
        'hour_two',
        'total_hours',
    ];
}
