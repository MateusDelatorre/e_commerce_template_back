<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Get ecommerce dashboard statistics.
     * Admin, owner, and developer only.
     *
     * Returns:
     * - Total revenue (from delivered orders)
     * - Revenue in the last 7 and 30 days
     * - Orders count by status
     * - Top selling products
     * - Products bought in the last 7/30 days
     * - Total customers
     */
    public function dashboard(Request $request): JsonResponse
    {
        $now = Carbon::now();

        // Revenue stats (only from delivered orders)
        $totalRevenue = OrderModel::where('status', 'delivered')->sum('total');

        $revenue7days = OrderModel::where('status', 'delivered')
            ->where('updated_at', '>=', $now->copy()->subDays(7))
            ->sum('total');

        $revenue30days = OrderModel::where('status', 'delivered')
            ->where('updated_at', '>=', $now->copy()->subDays(30))
            ->sum('total');

        // Orders count grouped by status
        $ordersByStatus = OrderModel::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $totalOrders = OrderModel::count();

        // Products bought in last N days (from delivered orders)
        $productsSold7days = OrderModel::where('status', 'delivered')
            ->where('updated_at', '>=', $now->copy()->subDays(7))
            ->withSum('items', 'quantity')
            ->get()
            ->sum('items_sum_quantity') ?? 0;

        $productsSold30days = OrderModel::where('status', 'delivered')
            ->where('updated_at', '>=', $now->copy()->subDays(30))
            ->withSum('items', 'quantity')
            ->get()
            ->sum('items_sum_quantity') ?? 0;

        // Top 10 best-selling products
        $topProducts = ProductModel::query()
            ->where('total_sold', '>', 0)
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get(['id', 'name', 'price', 'total_sold', 'stock']);

        // Total customer count
        $totalCustomers = User::where('role', 'customer')->count();

        // Low stock alert (products with stock <= 5 and stock > 0)
        $lowStockProducts = ProductModel::query()
            ->where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get(['id', 'name', 'stock']);

        // Out of stock count
        $outOfStockCount = ProductModel::where('stock', 0)->count();

        return response()->json([
            'revenue' => [
                'total'     => round($totalRevenue, 2),
                'last_7_days'  => round($revenue7days, 2),
                'last_30_days' => round($revenue30days, 2),
            ],
            'orders' => [
                'total'     => $totalOrders,
                'by_status' => $ordersByStatus,
            ],
            'products_sold' => [
                'last_7_days'  => (int) $productsSold7days,
                'last_30_days' => (int) $productsSold30days,
            ],
            'top_products'      => $topProducts,
            'total_customers'   => $totalCustomers,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_count' => $outOfStockCount,
        ]);
    }
}
