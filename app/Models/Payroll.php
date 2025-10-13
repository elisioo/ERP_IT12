<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'period', 'total_hours', 'hourly_rate', 
        'gross_pay', 'status', 'pay_date'
    ];

    protected $casts = [
        'pay_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
}
