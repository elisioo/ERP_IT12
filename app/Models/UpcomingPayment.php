<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingPayment extends Model
{
    protected $fillable = ['title', 'date', 'icon'];
}