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

        $employees = Employee::active()->with(['attendances' => function($q) use ($selectedDate) {
            $q->where('date', $selectedDate);
        }])
        ->orderBy('first_name', 'asc')
        ->orderBy('last_name', 'asc')
        ->get();

        $allEmployees = Employee::orderBy('first_name', 'asc')
        ->orderBy('last_name', 'asc')
        ->get();

        return view('employee.attendance', compact('employees', 'selectedDate', 'allEmployees'));
    }

    /**
     * Update (or create) today's attendance for an employee
     */
    public function update(Request $request, $id)
    {
        $existingAttendance = Attendance::where('employee_id', $id)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance && $existingAttendance->time_out) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit attendance once time out is recorded.'
            ], 403);
        }

        $request->merge([
            'time_in' => $request->time_in === '' ? null : $request->time_in,
            'time_out' => $request->time_out === '' ? null : $request->time_out,
        ]);

        $request->merge([
            'time_in' => $request->time_in ? substr($request->time_in, 0, 5) : null,
            'time_out' => $request->time_out ? substr($request->time_out, 0, 5) : null,
        ]);

        $request->validate([
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'date' => 'required|date',
        ]);

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
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Employee::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('employee.dashboard')->with('success', 'Employee added.');
    }

    /**
     * Archive an employee
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->archived_at = now();
        $employee->save();

        return redirect()->back()->with('success', 'Employee archived successfully.');
    }

    /**
     * Restore an archived employee
     */
    public function restore($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->archived_at = null;
        $employee->save();

        return redirect()->back()->with('success', 'Employee restored successfully.');
    }

    /**
     * Permanently delete an employee
     */
    public function forceDelete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'Employee permanently deleted.');
    }
}
