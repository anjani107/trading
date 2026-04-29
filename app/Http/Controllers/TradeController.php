<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Trade::query();

        if ($request->filled('search')) {
            $query->where('stock', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('result')) {
            $request->result === 'win'
                ? $query->wins()
                : $query->losses();
        }

        $trades = $query->orderBy('trade_date', 'desc')->paginate(15)->withQueryString();

        return view('trades.index', compact('trades'));
    }

    public function create()
    {
        return view('trades.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTrade($request);
        Trade::create($data);

        return redirect()->route('trades.index')->with('success', 'Trade logged successfully!');
    }

    public function show(Trade $trade)
    {
        return view('trades.show', compact('trade'));
    }

    public function edit(Trade $trade)
    {
        return view('trades.edit', compact('trade'));
    }

    public function update(Request $request, Trade $trade)
    {
        $data = $this->validateTrade($request);
        $trade->update($data);

        return redirect()->route('trades.index')->with('success', 'Trade updated successfully!');
    }

    public function destroy(Trade $trade)
    {
        $trade->delete();

        return redirect()->route('trades.index')->with('success', 'Trade deleted.');
    }

    private function validateTrade(Request $request): array
    {
        return $request->validate([
            'trade_date' => 'required|date',
            'stock'      => 'required|string|max:50',
            'type'       => 'required|in:Intraday,Swing,Positional,Long Term,Options,Futures',
            'entry'      => 'required|numeric|min:0',
            'exit'       => 'required|numeric|min:0',
            'quantity'   => 'nullable|integer|min:1',
            'pnl'        => 'nullable|numeric',
            'reason'     => 'nullable|string|max:1000',
            'mistake'    => 'nullable|string|max:1000',
        ]);
    }
}
