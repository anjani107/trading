@extends('layouts.app')

@section('title', 'All Trades')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">All Trades</h1>
        <p class="text-slate-500">{{ $trades->total() }} trades logged</p>
    </div>
    <a href="{{ route('trades.create') }}"
       class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-md font-semibold">
        + New Trade
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search stock..." class="border rounded-md px-3 py-2">
    <select name="type" class="border rounded-md px-3 py-2">
        <option value="">All Types</option>
        @foreach(['Intraday', 'Swing', 'Positional', 'Long Term', 'Options', 'Futures'] as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>
    <select name="result" class="border rounded-md px-3 py-2">
        <option value="">All Results</option>
        <option value="win" {{ request('result') === 'win' ? 'selected' : '' }}>Wins Only</option>
        <option value="loss" {{ request('result') === 'loss' ? 'selected' : '' }}>Losses Only</option>
    </select>
    <div class="flex gap-2">
        <button type="submit" class="flex-1 bg-slate-700 text-white rounded-md px-4 py-2 hover:bg-slate-800">Filter</button>
        <a href="{{ route('trades.index') }}" class="flex-1 text-center border rounded-md px-4 py-2 hover:bg-slate-50">Reset</a>
    </div>
</form>

{{-- Trades Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b text-slate-600">
                <tr>
                    <th class="text-left py-3 px-4">Date</th>
                    <th class="text-left py-3 px-4">Stock</th>
                    <th class="text-left py-3 px-4">Type</th>
                    <th class="text-right py-3 px-4">Entry</th>
                    <th class="text-right py-3 px-4">Exit</th>
                    <th class="text-right py-3 px-4">P/L</th>
                    <th class="text-left py-3 px-4">Reason</th>
                    <th class="text-left py-3 px-4">Mistake</th>
                    <th class="text-right py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trades as $trade)
                    <tr class="border-b last:border-0 hover:bg-slate-50">
                        <td class="py-3 px-4">{{ $trade->trade_date->format('d M Y') }}</td>
                        <td class="py-3 px-4 font-medium">{{ $trade->stock }}</td>
                        <td class="py-3 px-4">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $trade->type }}</span>
                        </td>
                        <td class="py-3 px-4 text-right">₹{{ number_format($trade->entry, 2) }}</td>
                        <td class="py-3 px-4 text-right">₹{{ number_format($trade->exit, 2) }}</td>
                        <td class="py-3 px-4 text-right font-semibold {{ $trade->pnl >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $trade->pnl >= 0 ? '+' : '' }}₹{{ number_format($trade->pnl, 2) }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 max-w-xs truncate" title="{{ $trade->reason }}">
                            {{ $trade->reason ?: '—' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 max-w-xs truncate" title="{{ $trade->mistake }}">
                            {{ $trade->mistake ?: 'None' }}
                        </td>
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            <a href="{{ route('trades.edit', $trade) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="{{ route('trades.destroy', $trade) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this trade?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            No trades found. <a href="{{ route('trades.create') }}" class="text-blue-600 hover:underline">Log your first trade</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $trades->links() }}
</div>
@endsection
