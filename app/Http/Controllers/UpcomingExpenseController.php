<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpcomingPayment;

class UpcomingExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'icon' => 'nullable|string|max:255',
        ]);

        UpcomingPayment::create([
            'title' => $request->title,
            'icon' => $request->icon ?: 'fa-solid fa-calendar',
            'date' => $request->date,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Upcoming payment added.');
    }

    public function markPaid($id)
    {
        $payment = UpcomingPayment::findOrFail($id);
        $payment->status = 'paid';
        $payment->save();

        return redirect()->back()->with('success', "{$payment->title} marked as paid.");
    }

    public function unmark($id)
    {
        $payment = UpcomingPayment::findOrFail($id);
        $payment->status = 'pending';
        $payment->save();

        return redirect()->back()->with('success', "{$payment->title} moved back to upcoming.");
    }
}