<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'name',
        'division',
        'date',
        'check_in',
        'check_out',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'status'
    ];

    /**
     * Get the week number (ISO standard or custom relative).
     */
    public function getWeekAttribute()
    {
        return Carbon::parse($this->date)->isoWeek();
    }
}
