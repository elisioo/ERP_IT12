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
    public function attendance(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());

        $employees = Employee::with(['attendances' => function($q) use ($selectedDate) {
            $q->where('date', $selectedDate);
        }])
        ->orderBy('first_name', 'asc')
        ->orderBy('last_name', 'asc')
        ->get();

        return view('employee.attendance', compact('employees', 'selectedDate'));
    }

    /**
     * Update (or create) today's attendance for an employee
     */
    public function update(Request $request, $id)
    {
        // Normalize empty inputs to null
        $request->merge([
            'time_in' => $request->time_in === '' ? null : $request->time_in,
            'time_out' => $request->time_out === '' ? null : $request->time_out,
        ]);

        // 🕒 Remove seconds if present (e.g. "08:30:00" -> "08:30")
        $request->merge([
            'time_in' => $request->time_in ? substr($request->time_in, 0, 5) : null,
            'time_out' => $request->time_out ? substr($request->time_out, 0, 5) : null,
        ]);

        // ✅ Validate inputs
        $request->validate([
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'date' => 'required|date',
        ]);

        // ✅ Update or create attendance record
        $attendance = Attendance::updateOrCreate(
            ['employee_id' => $id, 'date' => $request->date],
            [
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
            ]
        );

        return response()->json([
            'success' => true,
            'time_in' => $attendance->time_in,
            'time_out' => $attendance->time_out,
        ]);
    }

    /**
     * Add a new employee (used by the Add Employee modal)
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        Employee::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Employee added.');
    }

    /**
     * Delete an employee
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // Cascade deletes attendance if FK setup

        return redirect()->back()->with('success', 'Employee deleted.');
    }
}
