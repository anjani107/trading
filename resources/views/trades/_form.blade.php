@csrf

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Date *</label>
        <input type="date" name="trade_date"
               value="{{ old('trade_date', isset($trade) ? $trade->trade_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
               class="w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Stock *</label>
        <input type="text" name="stock" placeholder="e.g. Reliance"
               value="{{ old('stock', $trade->stock ?? '') }}"
               class="w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
        <select name="type" class="w-full border rounded-md px-3 py-2" required>
            @foreach(['Intraday', 'Swing', 'Positional', 'Long Term', 'Options', 'Futures'] as $t)
                <option value="{{ $t }}" {{ old('type', $trade->type ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
        <input type="number" name="quantity" min="1" placeholder="1"
               value="{{ old('quantity', $trade->quantity ?? 1) }}"
               class="w-full border rounded-md px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Entry Price *</label>
        <input type="number" name="entry" step="0.01" placeholder="2505"
               value="{{ old('entry', $trade->entry ?? '') }}"
               class="w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Exit Price *</label>
        <input type="number" name="exit" step="0.01" placeholder="2545"
               value="{{ old('exit', $trade->exit ?? '') }}"
               class="w-full border rounded-md px-3 py-2" required>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">
            P/L <span class="text-slate-400 font-normal">(leave blank to auto-calculate from Entry × Exit × Quantity)</span>
        </label>
        <input type="number" name="pnl" step="0.01" placeholder="+1600 (auto-calculated if empty)"
               value="{{ old('pnl', $trade->pnl ?? '') }}"
               class="w-full border rounded-md px-3 py-2">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Reason for Trade</label>
        <textarea name="reason" rows="2" placeholder="e.g. Breakout + volume"
                  class="w-full border rounded-md px-3 py-2">{{ old('reason', $trade->reason ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Mistake (if any)</label>
        <textarea name="mistake" rows="2" placeholder="e.g. None / Entered too early / No stop loss"
                  class="w-full border rounded-md px-3 py-2">{{ old('mistake', $trade->mistake ?? '') }}</textarea>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-md font-semibold">
        {{ isset($trade) ? 'Update Trade' : 'Save Trade' }}
    </button>
    <a href="{{ route('trades.index') }}" class="border px-6 py-2 rounded-md hover:bg-slate-50">Cancel</a>
</div>
