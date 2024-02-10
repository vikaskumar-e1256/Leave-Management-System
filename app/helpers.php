<?php

use Carbon\Carbon;
use App\Models\LeaveType;
use App\Models\LeaveAllocation;
use App\Models\LeaveApplication;

if (!function_exists('currentFiscalYear')) {
    function currentFiscalYear()
    {
        $currentDate = Carbon::now();

        $fiscalYearStart = config('leave_policies.fiscal_year_start');
        $fiscalYearStartMonth = intval(explode('-', $fiscalYearStart)[0]);
        $fiscalYearStartDay = intval(explode('-', $fiscalYearStart)[1]);

        $fiscalYearEnd = config('leave_policies.fiscal_year_end');
        $fiscalYearEndMonth = intval(explode('-', $fiscalYearEnd)[0]);
        $fiscalYearEndDay = intval(explode('-', $fiscalYearEnd)[1]);


        if ($currentDate->month > $fiscalYearStartMonth || ($currentDate->month == $fiscalYearStartMonth && $currentDate->day >= $fiscalYearStartDay)) {
            $currentYear = $currentDate->year;
            $nextYear = $currentYear + 1;
        } else {
            $currentYear = $currentDate->year - 1;
            $nextYear = $currentYear + 1;
        }

        $fiscalYear = "$currentYear-$nextYear";
        $startOfYear = Carbon::createFromFormat('Y-m-d', $currentYear . "-$fiscalYearStartMonth-$fiscalYearStartDay");
        $endOfYear = Carbon::createFromFormat('Y-m-d', $nextYear . "-$fiscalYearEndMonth-$fiscalYearEndDay");
        $startOfYearMonthDay = Carbon::createFromFormat('Y-m-d', $currentYear . "-$fiscalYearStartMonth-$fiscalYearStartDay");
        $endOfYearMonthDay = Carbon::createFromFormat('Y-m-d', $nextYear . "-$fiscalYearEndMonth-$fiscalYearEndDay");
        return [
            'startOfYear' => $startOfYear,
            'endOfYear' => $endOfYear,
            'fiscalYear' => $fiscalYear,
            'startOfYearMonthDay' => $startOfYearMonthDay,
            'endOfYearMonthDay' => $endOfYearMonthDay
        ];
    }
}


if (!function_exists('getUserLeaveTotals')) {
    function getUserLeaveTotals($userId, $fiscalYear)
    {
        $leaveTypeIds = LeaveType::where(['fiscal_year' => $fiscalYear, 'is_active' => true])->pluck('id', 'code');

        $totals = [];

        foreach ($leaveTypeIds as $code => $id) {
            $totals[$code] = 0;
        }

        $leaveAllocations = LeaveAllocation::where('user_id', $userId)
            ->where('fiscal_year', $fiscalYear)
            ->get();

        foreach ($leaveAllocations as $allocation) {
            $totals[$allocation->leaveType->code] += $allocation->allocated_amount;
        }

        return $totals;
    }
}

if (!function_exists('getUserUsedLeaveTotals')) {
    function getUserUsedLeaveTotals($userId, $fiscalYear)
    {
        $leaveTypeCodes = LeaveType::where(['fiscal_year' => $fiscalYear, 'is_active' => true])->pluck('code', 'id');

        $totals = [];

        foreach ($leaveTypeCodes as $id => $code) {
            $totals[$code] = 0;
        }

        $leaveApplications = LeaveApplication::where('user_id', $userId)
            ->where('fiscal_year', $fiscalYear)
            ->where('status', 'APPROVED')
            ->get();

        foreach ($leaveApplications as $application) {
            $totals[$application->leaveType->code] += $application->leave_days;
        }

        return $totals;
    }
}
