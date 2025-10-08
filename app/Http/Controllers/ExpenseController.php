<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\UpcomingPayment;
class ExpenseController extends Controller
{
    // Show all expenses
    public function index()
    {
        $defaultPayments = [
            ['title' => 'Electric Bill', 'icon' => 'fa-solid fa-bolt'],
            ['title' => 'Water Bill', 'icon' => 'fa-solid fa-faucet'],
            ['title' => 'Internet Bill', 'icon' => 'fa-solid fa-wifi'],
            ['title' => 'Rent', 'icon' => 'fa-solid fa-house'],
            ['title' => 'Tuition Fee', 'icon' => 'fa-solid fa-graduation-cap'],
        ];

        // Get all expenses
        $expenses = Expense::orderBy('date', 'desc')->get();

        // Total for this month
        $totalThisMonth = Expense::whereMonth('date', now()->month)
            ->sum('amount');

        // Total by category
        $categoryTotals = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Upcoming payments (example)
        $upcomingPayments = [
            ['title' => 'Rent Due', 'date' => now()->addDays(13), 'icon' => 'fa-solid fa-building text-primary'],
            ['title' => 'Electricity Bill', 'date' => now()->addDays(18), 'icon' => 'fa-solid fa-lightbulb text-warning'],
            ['title' => 'Staff Salary', 'date' => now()->addDays(23), 'icon' => 'fa-solid fa-users text-info'],
        ];
        $upcomingPaymentsCalendar = UpcomingPayment::orderBy('date', 'asc')->get();
        $upcomingPaymentsStatus = UpcomingPayment::where('status', 'pending')->orderBy('date')->get();
        $paidPayments = UpcomingPayment::where('status', 'paid')->orderByDesc('date')->get();

        return view('inventory.expenses', compact(
            'expenses',
            'totalThisMonth',
            'categoryTotals',
            'upcomingPayments',
            'upcomingPaymentsCalendar',
            'defaultPayments',
            'upcomingPaymentsStatus',
            'paidPayments'
        ), ['page' => 'expenses']);
    }


    // Show add expense form
    public function create()
    {
        return view('inventory.addExpenses', ['page' => 'expenses']);
    }

    // Store multiple expenses
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

    // Show edit expense form
    public function edit(Expense $expense)
    {
        return view('inventory.editExpenses', compact('expense'), ['page' => 'expenses']);
    }

    // Update single expense
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

    // Delete expense
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}