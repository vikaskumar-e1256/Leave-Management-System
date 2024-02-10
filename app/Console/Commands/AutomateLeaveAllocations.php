<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveAllocation;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AutomateLeaveAllocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automate-leave-allocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate leave allocations for all users based on their joining date and leave types.';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // 1. Get current date and financial year
        $currentFinancialYear  = currentFiscalYear();
        $fiscalYear = $currentFinancialYear['fiscalYear'];
        list($startFinancialYear, $endFinancialYear) = explode("-", $fiscalYear);

        // 2. Check for active leave types in current financial year
        $activeLeaveTypes = LeaveType::where(['fiscal_year' => $fiscalYear, 'is_active' => true])->get();

        if ($activeLeaveTypes->isEmpty()) {
            $this->info('No active leave types found for the current fiscal year.');
            return;
        }

        // 3. Process users in chunks for better performance
        User::active()->chunk(100, function($users) use ($activeLeaveTypes, $startFinancialYear, $endFinancialYear, $fiscalYear){
            foreach ($users as $user) {
                $this->processUserLeaveAllocations($user, $activeLeaveTypes, $startFinancialYear, $endFinancialYear, $fiscalYear);
            }
        });


        $this->info('Leave allocations updated or created successfully.');
    }

    /**
     * Process leave allocations for a single user.
     *
     * @param User $user
     * @param Collection $activeLeaveTypes
     * @param int $startFinancialYear
     * @param int $endFinancialYear
     */
    protected function processUserLeaveAllocations(User $user, Collection $activeLeaveTypes, int $startFinancialYear, int $endFinancialYear, $fiscalYear)
    {
        $joinDate = Carbon::parse($user->joining_date);
        $userJoinDay = $joinDate->day;
        $userJoinMonth = $joinDate->month;
        $userJoinYear = $joinDate->year;

        foreach ($activeLeaveTypes as $leaveType) {
            // Use a single query to optimize leave allocation retrieval
            $existingAllocations = LeaveAllocation::where([
                'user_id' => $user->id,
                'leave_type_id' => $leaveType->id,
            ])->get();
            if ($userJoinYear == $startFinancialYear) {
                // All good
            }else{
                $startFinancialYear = $userJoinYear;
            }
            // Loop over fiscal years from April to March
            for ($year = $startFinancialYear; $year <= $endFinancialYear; $year++) {
                $startMonth = ($year === $userJoinYear) ? $userJoinMonth : 4;
                $endMonth = ($year === $endFinancialYear) ? 3 : 12;

                // Handle special case for last financial year's endMonth
                if ($year === $endFinancialYear && $endMonth === 3) {
                    $startMonth = 1;
                    $endMonth = Carbon::now()->month;
                }

                foreach (range($startMonth, $endMonth) as $month) {
                    $monthYear = Carbon::createFromDate($year, $month, 1)->format('Y-m');

                    if ($existingAllocations->where('month_year', $monthYear)->first()) {
                        continue; // Skip existing allocation
                    }

                    $workedDays = $this->calculateWorkedDays($userJoinDay, $year, $month, $joinDate);

                    LeaveAllocation::create([
                        'user_id' => $user->id,
                        'leave_type_id' => $leaveType->id,
                        'fiscal_year' => $fiscalYear,
                        'month_year' => $monthYear,
                        'allocated_amount' => round(($workedDays / 365) * $leaveType->annual_leave_allocation_limit, 2),
                    ]);
                }
            }
        }
    }

    /**
     * Calculates the number of worked days for a given month.
     *
     * @param int $userJoinDay
     * @param int $year
     * @param int $month
     * @param Carbon $joinDate
     * @return int
     */
    private function calculateWorkedDays(int $userJoinDay, int $year, int $month, Carbon $joinDate): int
    {
        if ($userJoinDay > 1 && $year === $joinDate->year && $month === $joinDate->month) {
            return Carbon::createFromDate($year, $month, 1)->endOfMonth()->day - $userJoinDay;
        } else {
            return Carbon::createFromDate($year, $month, 1)->endOfMonth()->day;
        }
    }
}
