@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500">Overview of your trading performance</p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Total P/L</p>
        <p class="text-2xl font-bold {{ $totalPnl >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
            ₹{{ number_format($totalPnl, 2) }}
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Win Rate</p>
        <p class="text-2xl font-bold text-slate-800">{{ $winRate }}%</p>
        <p class="text-xs text-slate-400">{{ $winningTrades }}W / {{ $losingTrades }}L</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Total Trades</p>
        <p class="text-2xl font-bold text-slate-800">{{ $totalTrades }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Avg Win / Loss</p>
        <p class="text-lg font-bold">
            <span class="text-emerald-600">₹{{ number_format($avgWin, 0) }}</span>
            <span class="text-slate-400">/</span>
            <span class="text-red-600">₹{{ number_format($avgLoss, 0) }}</span>
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Best Trade</p>
        <p class="text-2xl font-bold text-emerald-600">₹{{ number_format($bestTrade, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Worst Trade</p>
        <p class="text-2xl font-bold text-red-600">₹{{ number_format($worstTrade, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Winning</p>
        <p class="text-2xl font-bold text-emerald-600">{{ $winningTrades }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-slate-500 mb-1">Losing</p>
        <p class="text-2xl font-bold text-red-600">{{ $losingTrades }}</p>
    </div>
</div>

{{-- Charts Row 1 --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold text-slate-700 mb-4">Equity Curve (Cumulative P/L)</h2>
        <canvas id="equityChart" height="120"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold text-slate-700 mb-4">Monthly P/L</h2>
        <canvas id="monthlyChart" height="120"></canvas>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-5">
        <h2 class="font-semibold text-slate-700 mb-4">Win / Loss</h2>
        <canvas id="winLossChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-5 lg:col-span-2">
        <h2 class="font-semibold text-slate-700 mb-4">Top Stocks by P/L</h2>
        @if($topStocks->count())
            <table class="w-full text-sm">
                <thead class="text-slate-500 border-b">
                    <tr>
                        <th class="text-left py-2">Stock</th>
                        <th class="text-right py-2">Trades</th>
                        <th class="text-right py-2">Total P/L</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topStocks as $stock)
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-medium">{{ $stock->stock }}</td>
                            <td class="py-2 text-right">{{ $stock->trades_count }}</td>
                            <td class="py-2 text-right font-semibold {{ $stock->total >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                ₹{{ number_format($stock->total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-slate-400 text-center py-8">No trades yet.</p>
        @endif
    </div>
</div>

{{-- Recent Trades --}}
<div class="bg-white rounded-lg shadow p-5">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold text-slate-700">Recent Trades</h2>
        <a href="{{ route('trades.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
    </div>
    @if($recentTrades->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-500 border-b bg-slate-50">
                    <tr>
                        <th class="text-left py-2 px-2">Date</th>
                        <th class="text-left py-2 px-2">Stock</th>
                        <th class="text-left py-2 px-2">Type</th>
                        <th class="text-right py-2 px-2">Entry</th>
                        <th class="text-right py-2 px-2">Exit</th>
                        <th class="text-right py-2 px-2">P/L</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTrades as $trade)
                        <tr class="border-b last:border-0 hover:bg-slate-50">
                            <td class="py-2 px-2">{{ $trade->trade_date->format('d M') }}</td>
                            <td class="py-2 px-2 font-medium">{{ $trade->stock }}</td>
                            <td class="py-2 px-2">
                                <span class="text-xs bg-slate-200 px-2 py-1 rounded">{{ $trade->type }}</span>
                            </td>
                            <td class="py-2 px-2 text-right">{{ $trade->entry }}</td>
                            <td class="py-2 px-2 text-right">{{ $trade->exit }}</td>
                            <td class="py-2 px-2 text-right font-semibold {{ $trade->pnl >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $trade->pnl >= 0 ? '+' : '' }}₹{{ number_format($trade->pnl, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-slate-400 text-center py-8">No trades logged yet. <a href="{{ route('trades.create') }}" class="text-blue-600 hover:underline">Log your first trade</a></p>
    @endif
</div>

<script>
    // Equity Curve
    new Chart(document.getElementById('equityChart'), {
        type: 'line',
        data: {
            labels: @json($equityLabels),
            datasets: [{
                label: 'Cumulative P/L',
                data: @json($equityData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: false } }
        }
    });

    // Monthly P/L
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'P/L',
                data: @json($monthlyData),
                backgroundColor: @json($monthlyData).map(v => v >= 0 ? '#10b981' : '#ef4444'),
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Win/Loss Doughnut
    new Chart(document.getElementById('winLossChart'), {
        type: 'doughnut',
        data: {
            labels: ['Wins', 'Losses'],
            datasets: [{
                data: @json($winLossData),
                backgroundColor: ['#10b981', '#ef4444'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endsection
