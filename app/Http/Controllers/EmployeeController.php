<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $employees = collect([
            (object)[
                'name' => 'John Doe',
                'position' => 'Software Engineer',
                'status' => 'Present',
                'hired_date' => '2022-01-15'
            ],
            (object)[
                'name' => 'Jane Smith',
                'position' => 'HR Manager',
                'status' => 'Absent',
                'hired_date' => '2021-09-20'
            ],
            (object)[
                'name' => 'Michael Johnson',
                'position' => 'Accountant',
                'status' => 'Leave',
                'hired_date' => '2020-05-10'
            ],
            (object)[
                'name' => 'Emily Brown',
                'position' => 'Designer',
                'status' => 'Present',
                'hired_date' => '2023-03-01'
            ],
        ]);

        // Calculate stats
        $employeeCount = $employees->count();
        $presentCount = $employees->where('status', 'Present')->count();
        $absentCount = $employees->where('status', 'Absent')->count();
        $leaveCount = $employees->where('status', 'Leave')->count();
        return view('employee.dashboard', compact('employeeCount', 'absentCount', 'leaveCount', 'presentCount', 'employees'));
    }
}
