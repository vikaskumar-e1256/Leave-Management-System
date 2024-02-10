<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveAllocation;
use Illuminate\Console\Command;

class AutomateLeaveAllocationsRawCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automate-leave-allocationsdd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Get current date and financial year
        $currentDate = Carbon::now()->endOfMonth();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        $startFinancialYear = $currentYear - 1;
        $endFinancialYear = $currentYear;

        // Generate fiscal year
        $financialYear = "$startFinancialYear-$endFinancialYear";

        $isLeaveTypeAvailableInCurrentFinancialYear = LeaveType::where(['fiscal_year' => $financialYear, 'is_active' => true])->count();
        if ($isLeaveTypeAvailableInCurrentFinancialYear > 0) {
            $users = User::active()->get();
            foreach ($users as $user) {
                $joinDate = Carbon::parse($user->joining_date);
                $userJoinDay = $joinDate->day;
                $startMonth = $userJoinMonth = $joinDate->month;
                $userJoinYear = $joinDate->year;
                $userJoinMonthDays = $joinDate->endOfMonth();

                if ($userJoinYear == $startFinancialYear) {
                    // All good
                } else {
                    $startFinancialYear = $userJoinYear;
                }
                // Loop over each fiscal year from April to March
                for ($year = $startFinancialYear; $year <= $endFinancialYear; $year++) {

                    $startMonth = ($year == $userJoinYear) ? $userJoinMonth : 4;
                    $endMonth = ($year == $endFinancialYear) ? 3 : 12;
                    // When loop run for $endFinancialYear then we need to startMonth = 1 & end month = $currentMonth
                    if ($year == $endFinancialYear && $endMonth == 3) {
                        $startMonth = 1;
                        $endMonth = $currentMonth;
                    }
                    // Loop over each month from the starting month to the ending month of the fiscal year
                    for ($month = $startMonth; $month <= $endMonth; $month++) {
                        // Check if data already exists for this user and month
                        $existingAllocation = LeaveAllocation::where([
                            'user_id' => $user->id,
                            'fiscal_year' => $financialYear,
                            'month_year' => Carbon::createFromDate($year, $month, 1)->format('Y-m'),
                        ])->exists();

                        // If data exists, skip to the next month
                        if ($existingAllocation) {
                            continue;
                        }

                        if ($userJoinDay > 1 && $userJoinYear == $year && $month == $userJoinMonth) {
                            $monthYear = Carbon::createFromDate($year, $month, 1)->format('Y-m');
                            $workedDays = $userJoinMonthDays->day - $userJoinDay;
                        } else {
                            $monthYear = Carbon::createFromDate($year, $month, 1)->format('Y-m');
                            $workedDays = Carbon::parse($monthYear)->endOfMonth()->day;
                        }

                        foreach (LeaveType::where(['fiscal_year' => $financialYear, 'is_active' => true, 'pro_rata_applicable' => true])->get() as $leaveType) {
                            $allocation = LeaveAllocation::updateOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'leave_type_id' => $leaveType->id,
                                    'fiscal_year' => $financialYear,
                                    'month_year' => $monthYear
                                ],
                                [
                                    'allocated_amount' => round(($workedDays / 365) * $leaveType->annual_leave_allocation_limit, 2)
                                ]
                            );
                        }
                    }
                }
            }
            $this->info('Leave allocations updated or created successfully.');
        }else {
            $this->info('No active leave types found for the current fiscal year.');
        }
    }
}
