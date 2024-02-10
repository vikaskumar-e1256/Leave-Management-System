<?php

namespace App\Http\Controllers;

use App\Models\LeaveAllocation;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function showLeaveBalances()
    {
        $userId = auth()->id();
        $currentFiscalYearData = currentFiscalYear();
        $currentFinancialYear = $currentFiscalYearData['fiscalYear'];

        $leaveBalances = LeaveAllocation::where([
            'fiscal_year' => $currentFinancialYear,
            'user_id' => $userId
        ])
            ->with('leaveType')
            ->get()
            ->groupBy(function ($allocation) {
                return Carbon::parse($allocation->month_year)->format('Y');
            })
            ->map(function ($yearData) {
                return $yearData->groupBy(function ($allocation) {
                    return Carbon::parse($allocation->month_year)->format('F Y');
                });
            });


        $totalEarnedLeaves = getUserLeaveTotals($userId, $currentFinancialYear);
        $totalUsedLeaves = getUserUsedLeaveTotals($userId, $currentFinancialYear);

        return view('leaves.show-leave-balances', [
            'leaveBalances' => $leaveBalances,
            'totalEarnedPL' => $totalEarnedLeaves['PL'],
            'totalEarnedCL' => $totalEarnedLeaves['CL'],
            'totalEarnedSL' => $totalEarnedLeaves['SL'],
            'totalUsedPL' => $totalUsedLeaves['PL'],
            'totalUsedCL' => $totalUsedLeaves['CL'],
            'totalUsedSL' => $totalUsedLeaves['SL'],
            'currentFinancialYear' => $currentFinancialYear
        ]);
    }

}
