<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;

class GeneratePayroll extends Command
{
    protected $signature = 'payroll:generate {--month= : Month in Y-m format} {--auto-pay : Automatically mark as paid}';
    protected $description = 'Automatically generate payroll for employees based on attendance';

    public function handle()
    {
        $month = $this->option('month') ?? now()->subMonth()->format('Y-m');
        $autoPay = $this->option('auto-pay');

        $this->info("Generating payroll for: {$month}");

        $employees = Employee::active()->with(['attendances' => function($q) use ($month) {
            $q->whereYear('date', Carbon::parse($month)->year)
              ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $generated = 0;
        $updated = 0;

        foreach ($employees as $employee) {
            $totalHours = $this->calculateTotalHours($employee->attendances);
            $hourlyRate = $employee->hourly_rate ?? 100;
            $grossPay = $totalHours * $hourlyRate;

            $payroll = Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'period' => $month],
                [
                    'total_hours' => $totalHours,
                    'hourly_rate' => $hourlyRate,
                    'gross_pay' => $grossPay,
                    'status' => $autoPay ? 'paid' : 'pending',
                    'pay_date' => $autoPay ? now() : null
                ]
            );

            if ($payroll->wasRecentlyCreated) {
                $generated++;
            } else {
                $updated++;
            }

            $this->line("✓ {$employee->full_name}: {$totalHours}h × ₱{$hourlyRate} = ₱{$grossPay}");
        }

        $this->info("Payroll generation completed!");
        $this->info("Generated: {$generated} new records");
        $this->info("Updated: {$updated} existing records");
    }

    private function calculateTotalHours($attendances)
    {
        $totalHours = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->time_in && $attendance->time_out) {
                $timeIn = strtotime($attendance->time_in);
                $timeOut = strtotime($attendance->time_out);
                if ($timeOut < $timeIn) {
                    $timeOut += 24 * 3600;
                }
                $seconds = $timeOut - $timeIn;
                $hours = $seconds / 3600;
                $totalHours += max(0, $hours);
            }
        }
        return round($totalHours, 2);
    }
}