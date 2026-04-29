<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_date',
        'stock',
        'type',
        'entry',
        'exit',
        'quantity',
        'pnl',
        'reason',
        'mistake',
    ];

    protected $casts = [
        'trade_date' => 'date',
        'entry'      => 'decimal:2',
        'exit'       => 'decimal:2',
        'pnl'        => 'decimal:2',
    ];

    /**
     * Auto-calculate P/L if not provided.
     */
    public static function booted(): void
    {
        static::saving(function (Trade $trade) {
            if (is_null($trade->pnl) && $trade->entry && $trade->exit) {
                $qty = $trade->quantity ?: 1;
                $trade->pnl = ($trade->exit - $trade->entry) * $qty;
            }
        });
    }

    public function getIsWinAttribute(): bool
    {
        return $this->pnl > 0;
    }

    public function scopeWins($query)
    {
        return $query->where('pnl', '>', 0);
    }

    public function scopeLosses($query)
    {
        return $query->where('pnl', '<', 0);
    }
}
