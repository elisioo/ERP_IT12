<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderLine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryDashboardController extends Controller
{
        public function index()
        {
            // Generate alerts
            $stockAlert = $this->generateStockAlert();

            //  Top 5 most bought menus
            $topMenus = OrderLine::select(
                    'menu_id',
                    DB::raw('SUM(quantity) as total_qty'),
                    DB::raw('SUM(quantity * price) as total_sales')
                )
                ->groupBy('menu_id')
                ->orderByDesc('total_qty')
                ->take(5)
                ->with(['menu.category'])
                ->get();

            //  Dashboard metrics
            $totalOrders = Order::count();
            $avgOrderValue = Order::avg('total_amount');
            $totalRevenue = Order::sum('total_amount');
            $topSellingMeal = $topMenus->first();

            //  Sales trend data (current month)
            $salesTrend = $this->getSalesTrend();

            //  Stock status for progress bar
            $stockStatus = $this->getStockStatus();

            return view('inventory.dashboard', [
                'soldMeals' => $topMenus,
                'totalOrders' => $totalOrders,
                'avgOrderValue' => $avgOrderValue,
                'totalRevenue' => $totalRevenue,
                'topSellingMeal' => $topSellingMeal,
                'stockAlert' => $stockAlert,
                'salesTrend' => $salesTrend,
                'stockStatus' => $stockStatus,
                'page' => 'dashboard'
            ]);
        }   

    private function getStockStatus()
    {
        $threshold = 10;

        // Count all inventory items below and above threshold
        $totalLowStock = Inventory::where('quantity', '<', $threshold)->count();
        $restockedLowStock = Inventory::where('quantity', '>=', $threshold)->count();

        $totalItems = $totalLowStock + $restockedLowStock;

        if ($totalItems === 0) {
            return [
                'percentage' => 100,
                'text' => 'No inventory data available',
            ];
        }

        $percentage = round(($restockedLowStock / $totalItems) * 100, 0);

        return [
            'percentage' => $percentage,
            'text' => "{$percentage}% of low-stock items restocked",
        ];
    }

/**
 * Generate alert for low-stock inventory
 */
    private function generateStockAlert()
    {
        $threshold = 10;

        // Get low-stock inventories with menu relationship
        $lowStockMenus = Inventory::with('menu')
            ->where('quantity', '<', $threshold)
            ->orderBy('quantity', 'asc')
            ->take(3)
            ->get();

        if ($lowStockMenus->isEmpty()) {
            return null;
        }

        // Collect menu names or fallback text if null
        $menuNames = $lowStockMenus->map(function ($inv) {
            return $inv->menu->menu_name ?? 'Not available';
        });

        $itemList = $menuNames->join(', ');

        return "Low stock alert: {$itemList} need to be reordered soon!";
    }
    /**
     *  Get total sales grouped by week for the current month
     */
    private function getSalesTrend()
    {
        $salesData = Order::select(
                DB::raw('WEEK(order_date) as week_number'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year)
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();

        // Prepare chart labels & values
        $labels = [];
        $values = [];

        foreach ($salesData as $data) {
            $labels[] = "Week " . ($data->week_number - Carbon::now()->startOfMonth()->week() + 1);
            $values[] = $data->total_sales;
        }

        // Fill missing weeks with 0 for a clean chart
        for ($i = 1; $i <= 4; $i++) {
            if (!isset($values[$i - 1])) {
                $labels[$i - 1] = "Week {$i}";
                $values[$i - 1] = 0;
            }
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}