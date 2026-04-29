<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $trades = Trade::all();

        $totalTrades   = $trades->count();
        $winningTrades = $trades->where('pnl', '>', 0)->count();
        $losingTrades  = $trades->where('pnl', '<', 0)->count();
        $totalPnl      = $trades->sum('pnl');
        $bestTrade     = $trades->max('pnl') ?? 0;
        $worstTrade    = $trades->min('pnl') ?? 0;
        $winRate       = $totalTrades > 0 ? round(($winningTrades / $totalTrades) * 100, 2) : 0;
        $avgWin        = $winningTrades > 0 ? round($trades->where('pnl', '>', 0)->sum('pnl') / $winningTrades, 2) : 0;
        $avgLoss       = $losingTrades > 0 ? round($trades->where('pnl', '<', 0)->sum('pnl') / $losingTrades, 2) : 0;

        // Equity curve (cumulative P/L over time)
        $equityCurve = Trade::orderBy('trade_date')
            ->get(['trade_date', 'pnl'])
            ->groupBy(fn ($t) => $t->trade_date->format('Y-m-d'))
            ->map(fn ($g) => $g->sum('pnl'));

        $cumulative = 0;
        $equityLabels = [];
        $equityData   = [];
        foreach ($equityCurve as $date => $pnl) {
            $cumulative += $pnl;
            $equityLabels[] = Carbon::parse($date)->format('d M');
            $equityData[]   = round($cumulative, 2);
        }

        // Monthly P/L
        $monthly = Trade::selectRaw("DATE_FORMAT('%Y-%m', trade_date) as month, SUM(pnl) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Fallback for MySQL (in case strftime not available)
        if ($monthly->isEmpty() && Trade::count() > 0) {
            $monthly = Trade::selectRaw("DATE_FORMAT(trade_date, '%Y-%m') as month, SUM(pnl) as total")
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        $monthlyLabels = $monthly->pluck('month')->map(fn ($m) => Carbon::parse($m . '-01')->format('M Y'));
        $monthlyData   = $monthly->pluck('total')->map(fn ($v) => round((float) $v, 2));

        // Win/Loss distribution
        $winLossData = [$winningTrades, $losingTrades];

        // Top stocks by P/L
        $topStocks = Trade::selectRaw('stock, SUM(pnl) as total, COUNT(*) as trades_count')
            ->groupBy('stock')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentTrades = Trade::orderBy('trade_date', 'desc')->limit(5)->get();

        return view('dashboard.index', compact(
            'totalTrades', 'winningTrades', 'losingTrades', 'totalPnl',
            'bestTrade', 'worstTrade', 'winRate', 'avgWin', 'avgLoss',
            'equityLabels', 'equityData',
            'monthlyLabels', 'monthlyData',
            'winLossData', 'topStocks', 'recentTrades'
        ));
    }
}
