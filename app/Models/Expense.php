<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'description',
        'amount',
        'status',
    ];

    // Cast 'date' to a Carbon instance automatically
    protected $casts = [
        'date' => 'datetime',
    ];
}