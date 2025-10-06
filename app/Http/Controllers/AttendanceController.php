<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Show attendance view with employees + their attendances
     */
    public function attendance()
    {
        // Load ALL attendances, not just today
        $employees = Employee::with('attendances')
            ->orderBy('first_name', 'asc')
            ->orderBy('last_name', 'asc')
            ->get();

        return view('employee.attendance', compact('employees'));
    }

    /**
     * Update (or create) today's attendance for an employee
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Present,Late,Absent',
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $id,
                'date' => now()->toDateString(),
            ],
            [
                'status' => $request->status,
            ]
        );

        // If AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $attendance->status
            ]);
        }

        // Fallback if not AJAX
        return redirect()->back()->with('success', 'Attendance updated successfully!');
    }


    /**
     * Add a new employee (used by the Add Employee modal in your blade)
     */
    public function store(Request $request)
    {
        $request->validate(['first_name' => 'required|string|max:255',
                            'last_name'  => 'required|string|max:255',
        ]);

        $employee = Employee::create(['first_name' => $request->first_name,
                                    'last_name' => $request->last_name,]);

        return redirect()->route('employee.dashboard')->with('success', 'Employee added.');
    }

    /**
     * Delete an employee
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // if you set foreign keys with cascade, attendances will be removed
        return redirect()->back()->with('success', 'Employee deleted.');
    }
}
