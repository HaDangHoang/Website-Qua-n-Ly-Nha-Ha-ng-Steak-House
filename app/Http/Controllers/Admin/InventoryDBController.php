<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\InventoryTransaction;
use App\Models\InventoryStock;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryDBController extends Controller
{
    public function index()
    {
        $title = 'Thống Kê kho';

        $supplierCount = Supplier::count();
        $ingredientCount = Ingredient::count();
        $transactionCount = InventoryTransaction::count();
        $stockCount = InventoryStock::count();


        // Dữ liệu trước đó để tính toán phần trăm thay đổi
        $previousSupplierCount = Supplier::whereDate('created_at', '<', now()->subMonth())->count();
        $previousIngredientCount = Ingredient::whereDate('created_at', '<', now()->subMonth())->count();
        $previousTransactionCount = InventoryTransaction::whereDate('created_at', '<', now()->subMonth())->count();
        $previousStockCount = InventoryStock::whereDate('created_at', '<', now()->subMonth())->count();

        // Tính phần trăm thay đổi
        $supplierChangePercent = $previousSupplierCount > 0 ? (($supplierCount - $previousSupplierCount) / $previousSupplierCount) * 100 : 0;
        $ingredientChangePercent = $previousIngredientCount > 0 ? (($ingredientCount - $previousIngredientCount) / $previousIngredientCount) * 100 : 0;
        $transactionChangePercent = $previousTransactionCount > 0 ? (($transactionCount - $previousTransactionCount) / $previousTransactionCount) * 100 : 0;
        $stockChangePercent = $previousStockCount > 0 ? (($stockCount - $previousStockCount) / $previousStockCount) * 100 : 0;

        // Tính tổng số lượng và phần trăm thay đổi tổng thể
        $totalCount = $supplierCount + $ingredientCount + $transactionCount + $stockCount;
        $previousTotalCount = $previousSupplierCount + $previousIngredientCount + $previousTransactionCount + $previousStockCount;
        $totalChangePercent = $previousTotalCount > 0 ? (($totalCount - $previousTotalCount) / $previousTotalCount) * 100 : 0;



        // Lấy dữ liệu sử dụng nguyên liệu hàng ngày (7 ngày gần đây)
        $dailyData = Ingredient::selectRaw('DATE(created_at) as date, SUM(price) as total_usage')  // Sử dụng price thay vì quantity
            ->where('created_at', '>=', Carbon::today()->subDays(7)) // Lấy 7 ngày gần đây
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($usage) {
                return [
                    'x' => $usage->date, // Trả về ngày sử dụng
                    'y' => $usage->total_usage // Tổng giá trị sử dụng (tính từ price)
                ];
            });

        // Lấy dữ liệu sử dụng nguyên liệu hàng tuần (4 tuần gần đây)
        $weeklyData = Ingredient::selectRaw('YEARWEEK(created_at) as week, SUM(price) as total_usage')
            ->where('created_at', '>=', Carbon::today()->subWeeks(4)) // Lấy 4 tuần gần đây
            ->groupBy('week')
            ->orderBy('week', 'asc')  // Sắp xếp theo tuần tăng dần
            ->get()
            ->map(function ($usage) {
                return [
                    'x' => $usage->week, // Tuần
                    'y' => $usage->total_usage // Tổng giá trị sử dụng (tính từ price)
                ];
            });

        $categoryData = DB::table('ingredients')
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get();






        // Lấy dữ liệu theo tuần và tháng cho các giao dịch
        $weeklyData = InventoryTransaction::selectRaw('status, COUNT(*) as total_count, WEEK(created_at) as week')
            ->groupBy('status', 'week')
            ->get();

        $monthlyData = InventoryTransaction::selectRaw('status, COUNT(*) as total_count, MONTH(created_at) as month')
            ->groupBy('status', 'month')
            ->get();

        // Lấy dữ liệu top 5 sản phẩm có số lượng stock cao nhất
        $topProducts = InventoryStock::with('ingredient')
            ->orderBy('quantity_stock', 'desc')
            ->take(5)
            ->get();

        // Dữ liệu cho biểu đồ
        $labels = $topProducts->map(function ($product) {
            return $product->ingredient->name;  // Tên sản phẩm
        });

        $series = $topProducts->map(function ($product) {
            return $product->quantity_stock;  // Số lượng tồn kho
        });


        $allPProducts = InventoryStock::with('ingredient')->get();

        // Chuẩn bị dữ liệu cho biểu đồ
        $allLabels = $allPProducts->map(function ($product) {
            return $product->ingredient->name;  // Tên nguyên liệu
        });

        $allSeries = $allPProducts->map(function ($product) {
            return $product->quantity_stock;  // Số lượng tồn kho
        });

        // Dữ liệu giao dịch
        $weeklyCompleted = 0;
        $weeklyCanceled = 0;
        $monthlyCompleted = 0;
        $monthlyCanceled = 0;

        foreach ($weeklyData as $transaction) {
            if ($transaction->status == 'hoàn thành') {
                $weeklyCompleted += $transaction->total_count;
            } elseif ($transaction->status == 'Hủy') {
                $weeklyCanceled += $transaction->total_count;
            }
        }

        foreach ($monthlyData as $transaction) {
            if ($transaction->status == 'hoàn thành') {
                $monthlyCompleted += $transaction->total_count;
            } elseif ($transaction->status == 'Hủy') {
                $monthlyCanceled += $transaction->total_count;
            }
        }

        // Dữ liệu cho biểu đồ giao dịch
        $transactionData = [
            [
                'name' => 'Hoàn thành',
                'data' => $weeklyData->where('status', 'hoàn thành')->map(function ($item) {
                    return ['x' => $item->week, 'y' => $item->total_count];
                })->toArray(),
            ],
            [
                'name' => 'Đã hủy',
                'data' => $weeklyData->where('status', 'Hủy')->map(function ($item) {
                    return ['x' => $item->week, 'y' => $item->total_count];
                })->toArray(),
            ],
        ];

        return view('admin.dashboard.inventoryDashboard.index', [
            'weeklyCompleted' => $weeklyCompleted,
            'weeklyCanceled' => $weeklyCanceled,
            'monthlyCompleted' => $monthlyCompleted,
            'monthlyCanceled' => $monthlyCanceled,
            'transactionData' => $transactionData,
            'labels' => $labels,
            'series' => $series,
            'allLabels' => $allLabels,
            'allSeries' => $series,
            'title' => $title,
            'supplierCount' => $supplierCount,
            'ingredientCount' => $ingredientCount,
            'transactionCount' => $transactionCount,
            'stockCount' => $stockCount,
            'supplierChangePercent' => $supplierChangePercent,
            'ingredientChangePercent' => $ingredientChangePercent,
            'transactionChangePercent' => $transactionChangePercent,
            'stockChangePercent' => $stockChangePercent,
            'totalCount' => $totalCount,
            'totalChangePercent' => $totalChangePercent,
            'topProducts' => $topProducts,
            'dailyData' => $dailyData,
            'weeklyData' => $weeklyData,
            'monthlyData' => $monthlyData,
            'categoryData' => $categoryData,
        ]);
    }
}
