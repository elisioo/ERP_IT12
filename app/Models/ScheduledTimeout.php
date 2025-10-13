<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledTimeout extends Model
{
    protected $fillable = [
        'employee_ids', 'scheduled_time', 'scheduled_date', 'executed', 'executed_at'
    ];

    protected $casts = [
        'employee_ids' => 'array',
        'executed' => 'boolean',
        'executed_at' => 'datetime',
        'scheduled_date' => 'date'
    ];
}
