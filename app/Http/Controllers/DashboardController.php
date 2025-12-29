<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. DATA KARTU RINGKASAN (STATISTIK ATAS)
        |--------------------------------------------------------------------------
        */
        $totalProducts = Product::count();
        $totalStock    = Product::sum('stock');
        $totalAsset    = Product::selectRaw('SUM(stock * price) as total')->value('total');

        $totalUsers        = User::count();
        $todayTransactions = Transaction::whereDate(
            'transaction_date',
            Carbon::today()
        )->count();

        /*
        |--------------------------------------------------------------------------
        | 2. DATA STOK MENIPIS
        |--------------------------------------------------------------------------
        */
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3. TRANSAKSI TERBARU (TABEL BAWAH)
        |--------------------------------------------------------------------------
        */
        $transactionQuery = Transaction::with(['product', 'user']);

        switch ($request->get('sort')) {
            case 'oldest':
                $transactionQuery->oldest();
                break;

            case 'price_high':
                $transactionQuery->orderBy('total_price', 'desc');
                break;

            case 'price_low':
                $transactionQuery->orderBy('total_price', 'asc');
                break;

            default:
                $transactionQuery->latest();
                break;
        }

        $recentTransactions = $transactionQuery->take(5)->get();

        /*
        |--------------------------------------------------------------------------
        | 4. AKTIVITAS TERAKHIR (ACTIVITY LOG)
        |--------------------------------------------------------------------------
        */
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 5. DATA GRAFIK (AJAX SUPPORT)
        |--------------------------------------------------------------------------
        */
        $range     = $request->get('chart_range', 'week');
        $chartData = $this->getChartData($range);

        // Jika request dari AJAX → kirim JSON
        if ($request->ajax()) {
            return response()->json($chartData);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalAsset',
            'totalUsers',
            'todayTransactions',
            'lowStockProducts',
            'recentTransactions',
            'recentLogs',
            'chartData',
            'range'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FUNGSI PENGOLAH DATA GRAFIK
    |--------------------------------------------------------------------------
    */
    private function getChartData(string $range): array
    {
        $dates       = [];
        $incomeData  = [];
        $expenseData = [];

        if ($range === 'day') {
            // Hari ini (per jam)
            for ($i = 0; $i < 24; $i++) {
                $dates[] = sprintf('%02d:00', $i);

                $incomeData[] = Transaction::whereDate('transaction_date', Carbon::today())
                    ->whereTime('transaction_date', '>=', "$i:00:00")
                    ->whereTime('transaction_date', '<=', "$i:59:59")
                    ->where('type', 'in')
                    ->sum('quantity');

                $expenseData[] = Transaction::whereDate('transaction_date', Carbon::today())
                    ->whereTime('transaction_date', '>=', "$i:00:00")
                    ->whereTime('transaction_date', '<=', "$i:59:59")
                    ->where('type', 'out')
                    ->sum('quantity');
            }
        } elseif ($range === 'month') {
            // 30 hari terakhir
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                $dates[] = $date->format('d M');

                $incomeData[] = Transaction::whereDate(
                    'transaction_date',
                    $date->toDateString()
                )->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereDate(
                    'transaction_date',
                    $date->toDateString()
                )->where('type', 'out')->sum('quantity');
            }
        } elseif ($range === 'year') {
            // 12 bulan terakhir
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);

                $dates[] = $date->format('M Y');

                $incomeData[] = Transaction::whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month)
                    ->where('type', 'in')
                    ->sum('quantity');

                $expenseData[] = Transaction::whereYear('transaction_date', $date->year)
                    ->whereMonth('transaction_date', $date->month)
                    ->where('type', 'out')
                    ->sum('quantity');
            }
        } else {
            // Default: 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                $dates[] = $date->format('d M');

                $incomeData[] = Transaction::whereDate(
                    'transaction_date',
                    $date->toDateString()
                )->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereDate(
                    'transaction_date',
                    $date->toDateString()
                )->where('type', 'out')->sum('quantity');
            }
        }

        return [
            'dates'   => $dates,
            'income' => $incomeData,
            'expense'=> $expenseData,
        ];
    }
}
