<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    use HasFactory;

    protected $table = 'DailyRecord';

    protected $fillable = [
        'date',
        'male_count',
        'female_count',
        'male_avg_age',
        'female_avg_age'
    ];
}
