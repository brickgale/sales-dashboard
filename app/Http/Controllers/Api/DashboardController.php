<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2010|max:2030',
        ]);

        $month = $request->input('month');
        $year = $request->input('year', date('Y'));

        // Build query constraints
        $dateConstraints = function ($query) use ($month, $year) {
            $query->whereYear('orders.created_at', $year);
            if ($month) {
                $query->whereMonth('orders.created_at', $month);
            }
        };

        // Total ordered pizzas (sum of quantities for the period)
        $totalOrderedPizzas = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where(function ($query) use ($month, $year) {
                $query->whereYear('orders.created_at', $year);
                if ($month) {
                    $query->whereMonth('orders.created_at', $month);
                }
            })
            ->sum('order_details.quantity');

        // Total orders for the period
        $totalOrders = Order::where(function ($query) use ($month, $year) {
            $query->whereYear('created_at', $year);
            if ($month) {
                $query->whereMonth('created_at', $month);
            }
        })->count();

        // Total sales (sum of price * quantity for the period)
        $totalSales = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('pizzas', 'order_details.pizza_id', '=', 'pizzas.id')
            ->where(function ($query) use ($dateConstraints) {
                $dateConstraints($query);
            })
            ->sum(DB::raw('pizzas.price * order_details.quantity'));

        return response()->json([
            'data' => [
                'total_pizzas' => $totalOrderedPizzas,
                'total_orders' => $totalOrders,
                'total_sales' => round($totalSales, 2),
                'period' => [
                    'month' => $month,
                    'year' => $year,
                ],
            ]
        ]);
    }

    /**
     * Get sales breakdown by month for the year
     */
    public function salesByMonth(Request $request)
    {
        $request->validate([
            'year' => 'nullable|integer|min:2010|max:2030',
        ]);

        $year = $request->input('year', date('Y'));

        $salesByMonth = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('pizzas', 'order_details.pizza_id', '=', 'pizzas.id')
            ->whereYear('orders.created_at', $year)
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(pizzas.price * order_details.quantity) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy(DB::raw('MONTH(orders.created_at)'))
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'month_name' => date('F', mktime(0, 0, 0, $item->month, 1)),
                    'total_sales' => round($item->total_sales, 2),
                    'total_orders' => $item->total_orders,
                ];
            });

        return response()->json([
            'data' => $salesByMonth,
            'year' => $year,
        ]);
    }
}
