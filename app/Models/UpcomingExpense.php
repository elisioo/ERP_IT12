<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingExpense extends Model
{
    protected $fillable = ['title', 'date', 'icon'];
}
