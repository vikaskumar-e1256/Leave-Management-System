<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyLeaveSnapshot extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'leave_type_id', 'month_year', 'opening_balance', 'earned', 'used', 'available'];
}
