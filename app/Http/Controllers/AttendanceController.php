<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ScheduledTimeout;

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
     * One-click time in/out toggle
     */
    public function toggle($id)
    {
        $today = now()->toDateString();
        $currentTime = now()->format('H:i');
        $displayTime = now()->format('g:i A');
        
        $attendance = Attendance::where('employee_id', $id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // First click - Time In
            $attendance = Attendance::create([
                'employee_id' => $id,
                'date' => $today,
                'time_in' => $currentTime,
                'time_out' => null
            ]);
            
            return response()->json([
                'success' => true,
                'action' => 'time_in',
                'time_in' => $displayTime,
                'message' => 'Time In recorded at ' . $displayTime
            ]);
        } elseif ($attendance->time_in && !$attendance->time_out) {
            // Second click - Time Out
            $attendance->update([
                'time_out' => $currentTime,
                'timeout_type' => 'manual'
            ]);
            
            return response()->json([
                'success' => true,
                'action' => 'time_out',
                'time_in' => \Carbon\Carbon::parse($attendance->time_in)->format('g:i A'),
                'time_out' => $displayTime,
                'message' => 'Time Out recorded at ' . $displayTime
            ]);
        } else {
            // Already completed
            return response()->json([
                'success' => false,
                'message' => 'Attendance already completed for today'
            ]);
        }
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

    public function autoTimeout(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'timeout_time' => 'required|date_format:H:i',
            'timeout_option' => 'required|in:immediate,scheduled'
        ]);

        $today = now()->toDateString();
        $timeoutTime = $request->timeout_time;
        
        if ($request->timeout_option === 'immediate') {
            $updated = $this->executeTimeouts($request->employee_ids, $timeoutTime, $today, 'auto_immediate');
            return redirect()->back()->with('success', "Auto time-out set for {$updated} employees at {$timeoutTime}.");
        } else {
            // Schedule for later
            ScheduledTimeout::create([
                'employee_ids' => $request->employee_ids,
                'scheduled_time' => $timeoutTime,
                'scheduled_date' => $today
            ]);
            
            $count = count($request->employee_ids);
            return redirect()->back()->with('success', "Scheduled auto time-out for {$count} employees at {$timeoutTime}.");
        }
    }
    
    private function executeTimeouts($employeeIds, $timeoutTime, $date, $timeoutType = 'auto_scheduled')
    {
        $updated = 0;
        
        foreach ($employeeIds as $employeeId) {
            $attendance = Attendance::where('employee_id', $employeeId)
                                  ->where('date', $date)
                                  ->whereNotNull('time_in')
                                  ->whereNull('time_out')
                                  ->first();

            if ($attendance) {
                $attendance->update([
                    'time_out' => $timeoutTime,
                    'timeout_type' => $timeoutType
                ]);
                $updated++;
            }
        }
        
        return $updated;
    }
    
    public function checkScheduledTimeouts()
    {
        $currentTime = now()->format('H:i');
        $today = now()->toDateString();
        
        $scheduledTimeouts = ScheduledTimeout::where('scheduled_date', $today)
                                           ->where('scheduled_time', '<=', $currentTime)
                                           ->where('executed', false)
                                           ->get();
        
        $totalProcessed = 0;
        
        foreach ($scheduledTimeouts as $scheduled) {
            $processed = $this->executeTimeouts(
                $scheduled->employee_ids, 
                $scheduled->scheduled_time, 
                $scheduled->scheduled_date
            );
            
            $scheduled->update([
                'executed' => true,
                'executed_at' => now()
            ]);
            
            $totalProcessed += $processed;
        }
        
        return response()->json([
            'processed' => $totalProcessed,
            'schedules_executed' => $scheduledTimeouts->count()
        ]);
    }
}
