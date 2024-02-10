<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'annual_leave_allocation_limit', 'taken_frequency', 'fiscal_year_start', 'fiscal_year_end', 'fiscal_year', 'is_active', 'pro_rata_applicable'];

    public function leaveAllocations()
    {
        return $this->hasMany(LeaveAllocation::class);
    }

    public function leaveApplications()
    {
        return $this->hasManyThrough(LeaveApplication::class, LeaveAllocation::class);
    }
}
