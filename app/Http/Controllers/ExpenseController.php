<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\UpcomingExpense;

class ExpenseController extends Controller
{
    public function index()
    {
        // ✅ Default payment list (for dropdown)
        $defaultPayments = [
            ['title' => 'Electric Bill', 'icon' => 'fa-solid fa-bolt'],
            ['title' => 'Water Bill', 'icon' => 'fa-solid fa-faucet'],
            ['title' => 'Internet Bill', 'icon' => 'fa-solid fa-wifi'],
            ['title' => 'Rent', 'icon' => 'fa-solid fa-house'],
            ['title' => 'Tuition Fee', 'icon' => 'fa-solid fa-graduation-cap'],
            ['title' => 'Others', 'icon' => 'fa-solid fa-coins'],
        ];

        //  Expenses
        $expenses = Expense::orderBy('date', 'desc')->get();

        //  Total for this month
        $totalThisMonth = Expense::whereMonth('date', now()->month)
            ->sum('amount');

        // ✅ Category breakdown
        $categoryTotals = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        //  Upcoming & Paid Payments (from DB)
        $upcomingPaymentsStatus = UpcomingExpense::where('status', 'pending')
            ->orderBy('date', 'asc')
            ->get();

        $paidPayments = UpcomingExpense::where('status', 'paid')
            ->orderByDesc('date')
            ->get();

        return view('inventory.expenses', [
            'page' => 'expenses',
            'expenses' => $expenses,
            'totalThisMonth' => $totalThisMonth,
            'categoryTotals' => $categoryTotals,
            'upcomingPaymentsStatus' => $upcomingPaymentsStatus,
            'paidPayments' => $paidPayments,
            'defaultPayments' => $defaultPayments,
        ]);
    }

    public function create()
    {
        return view('inventory.addExpenses', ['page' => 'expenses']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'expenses.*.date' => 'required|date',
            'expenses.*.category' => 'required|string|max:255',
            'expenses.*.description' => 'nullable|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0',
            'expenses.*.status' => 'required|in:paid,pending',
        ]);

        foreach ($request->expenses as $item) {
            Expense::create($item);
        }

        return redirect()->route('expenses.index')->with('success', 'Expenses saved successfully!');
    }

    public function edit(Expense $expense)
    {
        return view('inventory.editExpenses', compact('expense'), ['page' => 'expenses']);
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,pending',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}