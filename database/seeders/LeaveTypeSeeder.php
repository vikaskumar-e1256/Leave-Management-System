<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fiscalYearDates = currentFiscalYear();
        $startOfYear = $fiscalYearDates['startOfYear'];
        $endOfYear = $fiscalYearDates['endOfYear'];
        $fiscalYear = $fiscalYearDates['fiscalYear'];

        \DB::table('leave_types')->insert([
            [
                'code' => 'PL',
                'name' => 'Paid Leave',
                'annual_leave_allocation_limit' => 12,
                'taken_frequency' => 'monthly',
                'is_active' => true,
                'fiscal_year_start' => $startOfYear,
                'fiscal_year_end' => $endOfYear,
                'fiscal_year' => $fiscalYear,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'code' => 'CL',
                'name' => 'Casual Leave',
                'annual_leave_allocation_limit' => 2,
                'taken_frequency' => 'bi-annual',
                'is_active' => true,
                'fiscal_year_start' => $startOfYear,
                'fiscal_year_end' => $endOfYear,
                'fiscal_year' => $fiscalYear,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'code' => 'SL',
                'name' => 'Sick Leave',
                'annual_leave_allocation_limit' => 4,
                'taken_frequency' => 'quarterly',
                'is_active' => true,
                'fiscal_year_start' => $startOfYear,
                'fiscal_year_end' => $endOfYear,
                'fiscal_year' => $fiscalYear,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
