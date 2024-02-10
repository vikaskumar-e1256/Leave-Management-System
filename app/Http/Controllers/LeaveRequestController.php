<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use Illuminate\Validation\ValidationException;


class LeaveRequestController extends Controller
{

    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leaves.apply-leave', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $userId = auth()->id();
        $currentFiscalYearData = currentFiscalYear();
        $currentFinancialYear = $currentFiscalYearData['fiscalYear'];

        $leaveDuration = Carbon::parse($validatedData['end_date'])->diffInDays(Carbon::parse($validatedData['start_date'])) + 1;

        $this->ensureNoOverlappingLeaveRequests($userId, $validatedData['start_date'], $validatedData['end_date']);
        $this->ensureSufficientLeaveBalance($userId, $validatedData['leave_type_id'], $leaveDuration);

        LeaveApplication::create([
            'user_id' => $userId,
            'leave_type_id' => $validatedData['leave_type_id'],
            'from_date' => $validatedData['start_date'],
            'to_date' => $validatedData['end_date'],
            'reason' => $validatedData['reason'],
            'status' => 'APPROVED',
            'leave_days' => $leaveDuration,
            'fiscal_year' => $currentFinancialYear
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully!');
    }

    protected function ensureNoOverlappingLeaveRequests($userId, $startDate, $endDate)
    {
        if (LeaveApplication::where('user_id', $userId)
            ->whereBetween('from_date', [$startDate, $endDate])
            ->orWhereBetween('to_date', [$startDate, $endDate])
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'start_date' => 'You have already applied for leave during this period.',
            ]);
        }
    }

    protected function ensureSufficientLeaveBalance($userId, $leaveTypeId, $leaveDuration)
    {
        $leaveBalance = LeaveType::findOrFail($leaveTypeId)
            ->leaveAllocations()
            ->where('user_id', $userId)
            ->sum('allocated_amount');

        if ($leaveBalance < $leaveDuration) {
            throw ValidationException::withMessages([
                'leave_type_id' => 'You do not have enough leave balance for this period.',
            ]);
        }
    }


}
