<?php

namespace Database\Seeders;

use App\Models\Trade;
use Illuminate\Database\Seeder;

class TradeSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'trade_date' => '2026-04-28',
                'stock'      => 'Reliance',
                'type'       => 'Intraday',
                'entry'      => 2505,
                'exit'       => 2545,
                'quantity'   => 40,
                'pnl'        => 1600,
                'reason'     => 'Breakout + volume',
                'mistake'    => 'None',
            ],
            [
                'trade_date' => '2026-04-25',
                'stock'      => 'TCS',
                'type'       => 'Swing',
                'entry'      => 3850,
                'exit'       => 3920,
                'quantity'   => 10,
                'pnl'        => 700,
                'reason'     => 'Support bounce',
                'mistake'    => 'Booked profit too early',
            ],
            [
                'trade_date' => '2026-04-22',
                'stock'      => 'HDFC Bank',
                'type'       => 'Intraday',
                'entry'      => 1680,
                'exit'       => 1665,
                'quantity'   => 50,
                'pnl'        => -750,
                'reason'     => 'Bullish flag pattern',
                'mistake'    => 'Ignored market trend, no stop loss',
            ],
            [
                'trade_date' => '2026-04-20',
                'stock'      => 'Infosys',
                'type'       => 'Positional',
                'entry'      => 1450,
                'exit'       => 1525,
                'quantity'   => 25,
                'pnl'        => 1875,
                'reason'     => 'Earnings momentum',
                'mistake'    => 'None',
            ],
            [
                'trade_date' => '2026-04-15',
                'stock'      => 'Adani Ent',
                'type'       => 'Intraday',
                'entry'      => 2410,
                'exit'       => 2395,
                'quantity'   => 20,
                'pnl'        => -300,
                'reason'     => 'Reversal play',
                'mistake'    => 'Counter-trend trade',
            ],
        ];

        foreach ($samples as $row) {
            Trade::create($row);
        }
    }
}
