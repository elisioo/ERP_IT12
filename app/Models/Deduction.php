<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'payroll_id', 'type', 'time_unit', 'duration', 'reason', 'amount'
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
