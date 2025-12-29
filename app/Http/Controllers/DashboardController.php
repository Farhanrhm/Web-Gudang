<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Kartu Atas (Statistik - Tetap Statis)
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalAsset = Product::selectRaw('SUM(stock * price) as total')->value('total');

        // 2. Data Tabel Bawah (Aktivitas Terbaru - Ikut Filter Sort)
        $query = Transaction::with(['product', 'user']);
        
        if ($request->sort == 'oldest') {
            $query->oldest();
        } elseif ($request->sort == 'price_high') {
            $query->orderBy('total_price', 'desc');
        } elseif ($request->sort == 'price_low') {
            $query->orderBy('total_price', 'asc');
        } else {
            $query->latest();
        }
        
        $recentTransactions = $query->take(5)->get();

        // 3. LOGIKA GRAFIK
        $range = $request->get('chart_range', 'week'); // Default 1 minggu
        $chartData = $this->getChartData($range);

        // --- BAGIAN AJAX (PENTING) ---
        // Jika request datang dari JavaScript (AJAX), kirim JSON saja!
        if ($request->ajax()) {
            return response()->json($chartData);
        }

        // Jika request biasa (buka browser), kirim Halaman View
        return view('dashboard', compact(
            'totalProducts', 
            'totalStock', 
            'totalAsset', 
            'recentTransactions',
            'chartData',
            'range'
        ));
    }

    // Fungsi Pengolah Data Grafik (Logic Timeframe)
    private function getChartData($range)
    {
        $dates = [];
        $incomeData = [];
        $expenseData = [];

        if ($range == 'day') {
            // --- HARI INI (Per Jam: 00:00 - 23:00) ---
            for ($i = 0; $i <= 23; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $dates[] = $hour; 

                $incomeData[] = Transaction::whereDate('transaction_date', Carbon::today())
                                ->whereTime('transaction_date', '>=', "$i:00:00")
                                ->whereTime('transaction_date', '<=', "$i:59:59")
                                ->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereDate('transaction_date', Carbon::today())
                                ->whereTime('transaction_date', '>=', "$i:00:00")
                                ->whereTime('transaction_date', '<=', "$i:59:59")
                                ->where('type', 'out')->sum('quantity');
            }

        } elseif ($range == 'month') {
            // --- 1 BULAN (30 Hari Terakhir) ---
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dates[] = $date->format('d M'); 

                $incomeData[] = Transaction::whereDate('transaction_date', $date->format('Y-m-d'))
                                ->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereDate('transaction_date', $date->format('Y-m-d'))
                                ->where('type', 'out')->sum('quantity');
            }

        } elseif ($range == 'year') {
            // --- 1 TAHUN (Jan - Dec) ---
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $dates[] = $date->format('M Y'); 

                $incomeData[] = Transaction::whereYear('transaction_date', $date->year)
                                ->whereMonth('transaction_date', $date->month)
                                ->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereYear('transaction_date', $date->year)
                                ->whereMonth('transaction_date', $date->month)
                                ->where('type', 'out')->sum('quantity');
            }

        } else {
            // --- DEFAULT: 1 MINGGU (7 Hari Terakhir) ---
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dates[] = $date->format('d M'); 

                $incomeData[] = Transaction::whereDate('transaction_date', $date->format('Y-m-d'))
                                ->where('type', 'in')->sum('quantity');

                $expenseData[] = Transaction::whereDate('transaction_date', $date->format('Y-m-d'))
                                ->where('type', 'out')->sum('quantity');
            }
        }

        return [
            'dates' => $dates,
            'income' => $incomeData,
            'expense' => $expenseData
        ];
    }
}