<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample employees with different scenarios
        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '09123456789',
                'email' => 'john.doe@example.com',
                'hourly_rate' => 100.00,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'phone' => '09198765432',
                'email' => 'jane.smith@example.com',
                'hourly_rate' => 120.00,
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'phone' => '09234567890',
                'email' => 'bob.johnson@example.com',
                'hourly_rate' => 90.00,
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Williams',
                'phone' => '09345678901',
                'email' => 'alice.williams@example.com',
                'hourly_rate' => 110.00,
            ],
            [
                'first_name' => 'Charlie',
                'last_name' => 'Brown',
                'phone' => '09456789012',
                'email' => 'charlie.brown@example.com',
                'hourly_rate' => 95.00,
            ],
            [
                'first_name' => 'Diana',
                'last_name' => 'Davis',
                'phone' => '09567890123',
                'email' => 'diana.davis@example.com',
                'hourly_rate' => 130.00,
            ],
            [
                'first_name' => 'Edward',
                'last_name' => 'Miller',
                'phone' => '09678901234',
                'email' => 'edward.miller@example.com',
                'hourly_rate' => 85.00,
            ],
            [
                'first_name' => 'Fiona',
                'last_name' => 'Wilson',
                'phone' => '09789012345',
                'email' => 'fiona.wilson@example.com',
                'hourly_rate' => 115.00,
            ],
            [
                'first_name' => 'George',
                'last_name' => 'Moore',
                'phone' => '09890123456',
                'email' => 'george.moore@example.com',
                'hourly_rate' => 105.00,
            ],
            [
                'first_name' => 'Helen',
                'last_name' => 'Taylor',
                'phone' => '09901234567',
                'email' => 'helen.taylor@example.com',
                'hourly_rate' => 125.00,
            ],
            [
                'first_name' => 'Ian',
                'last_name' => 'Anderson',
                'phone' => '09112345678',
                'email' => 'ian.anderson@example.com',
                'hourly_rate' => 100.00,
            ],
            [
                'first_name' => 'Julia',
                'last_name' => 'Thomas',
                'phone' => '09223456789',
                'email' => 'julia.thomas@example.com',
                'hourly_rate' => 110.00,
            ],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }

        // Generate attendance data for the current month with different scenarios
        $this->generateAttendanceScenarios();
    }

    /**
     * Generate various attendance scenarios for testing
     */
    private function generateAttendanceScenarios()
    {
        $employees = Employee::all();
        $currentMonth = now()->format('Y-m');
        $today = now()->toDateString();

        foreach ($employees as $index => $employee) {
            $this->createEmployeeAttendanceScenario($employee, $index, $currentMonth, $today);
        }
    }

    /**
     * Create different attendance scenarios for each employee
     */
    private function createEmployeeAttendanceScenario($employee, $index, $currentMonth, $today)
    {
        $scenarios = [
            0 => 'perfect_attendance',     // Complete attendance every day
            1 => 'partial_attendance',     // Some days with time in only
            2 => 'irregular_attendance',   // Random attendance pattern
            3 => 'no_attendance',          // No attendance this month
            4 => 'late_arrivals',          // Always late but complete
            5 => 'early_departures',       // Leaves early sometimes
            6 => 'weekend_worker',         // Only works weekends
            7 => 'part_time',              // Works fewer days
            8 => 'overtime_worker',        // Works long hours
            9 => 'mixed_scenario',         // Combination of issues
            10 => 'recent_hire',           // Started mid-month
            11 => 'consistent_worker',     // Regular 8-hour days
        ];

        $scenario = $scenarios[$index % count($scenarios)];
        $this->$scenario($employee, $currentMonth, $today);
    }

    /**
     * Scenario 1: Perfect attendance every working day
     */
    private function perfect_attendance($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => '17:00',
                ]);
            }
        }
    }

    /**
     * Scenario 2: Partial attendance - some days missing time out
     */
    private function partial_attendance($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                $hasTimeOut = rand(0, 1); // 50% chance of missing time out
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => $hasTimeOut ? '17:00' : null,
                ]);
            }
        }
    }

    /**
     * Scenario 3: Irregular attendance pattern
     */
    private function irregular_attendance($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend() && rand(0, 2) > 0) { // 66% chance of working
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => '17:00',
                ]);
            }
        }
    }

    /**
     * Scenario 4: No attendance this month
     */
    private function no_attendance($employee, $currentMonth, $today)
    {
        // No attendance records created - employee hasn't worked this month
    }

    /**
     * Scenario 5: Always late but complete attendance
     */
    private function late_arrivals($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                $lateMinutes = rand(30, 120); // 30 minutes to 2 hours late
                $timeIn = Carbon::createFromTime(9, 0)->addMinutes($lateMinutes)->format('H:i');
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => $timeIn,
                    'time_out' => '17:00',
                ]);
            }
        }
    }

    /**
     * Scenario 6: Early departures sometimes
     */
    private function early_departures($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                $earlyDeparture = rand(0, 1); // 50% chance of early departure
                $timeOut = $earlyDeparture ? Carbon::createFromTime(15, 0)->addMinutes(rand(0, 120))->format('H:i') : '17:00';
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => $timeOut,
                ]);
            }
        }
    }

    /**
     * Scenario 7: Only works weekends
     */
    private function weekend_worker($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekend()) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '10:00',
                    'time_out' => '18:00',
                ]);
            }
        }
    }

    /**
     * Scenario 8: Part-time worker (works fewer days)
     */
    private function part_time($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend() && rand(0, 1)) { // 50% chance of working on weekdays
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => '13:00', // 4-hour shifts
                ]);
            }
        }
    }

    /**
     * Scenario 9: Overtime worker (long hours)
     */
    private function overtime_worker($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                $overtimeHours = rand(2, 4);
                $timeOut = Carbon::createFromTime(17, 0)->addHours($overtimeHours)->format('H:i');
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => $timeOut,
                ]);
            }
        }
    }

    /**
     * Scenario 10: Mixed scenario with various issues
     */
    private function mixed_scenario($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend() && rand(0, 4) > 0) { // 80% chance of working
                $scenario = rand(1, 3);
                switch ($scenario) {
                    case 1: // Normal day
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $date->toDateString(),
                            'time_in' => '09:00',
                            'time_out' => '17:00',
                        ]);
                        break;
                    case 2: // Late arrival
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $date->toDateString(),
                            'time_in' => '10:30',
                            'time_out' => '17:00',
                        ]);
                        break;
                    case 3: // Missing time out
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $date->toDateString(),
                            'time_in' => '09:00',
                            'time_out' => null,
                        ]);
                        break;
                }
            }
        }
    }

    /**
     * Scenario 11: Recent hire (started mid-month)
     */
    private function recent_hire($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-15'); // Started on the 15th
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '09:00',
                    'time_out' => '17:00',
                ]);
            }
        }
    }

    /**
     * Scenario 12: Consistent 8-hour worker
     */
    private function consistent_worker($employee, $currentMonth, $today)
    {
        $startDate = Carbon::parse($currentMonth . '-01');
        $endDate = min(Carbon::parse($today), Carbon::parse($currentMonth . '-01')->endOfMonth());

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isWeekend()) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => '08:00', // Earlier start
                    'time_out' => '16:00', // Earlier end, still 8 hours
                ]);
            }
        }
    }
}
