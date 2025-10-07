<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpcomingPayment;

class UpcomingExpenseController extends Controller
{

    public function store(Request $request)
    {
        UpcomingPayment::create([
            'title' => $request->title,
            'icon' => $request->icon ?: 'fa-solid fa-calendar',
            'date' => $request->date,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Upcoming payment added.');
    }

    // public function markPaid($id)
    // {
    //     $payment = UpcomingPayment::findOrFail($id);
    //     $payment->status = 'paid';
    //     $payment->save();

    //     return back()->with('success', "{$payment->title} marked as paid.");
    // }
    public function markPaid(Request $request, $id)
{
    $payment = UpcomingPayment::findOrFail($id);
    $payment->update(['status' => 'paid']);

    if ($request->ajax()) {
        return response()->json(['success' => true, 'id' => $id]);
    }

    return back()->with('success', "{$payment->title} marked as paid.");
}


    public function unmark($id)
    {
        $payment = UpcomingPayment::findOrFail($id);
        $payment->status = 'pending';
        $payment->save();

        return redirect()->back()->with('success', 'Payment moved back to upcoming.');
    }


}