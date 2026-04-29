<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->date('trade_date');
            $table->string('stock');
            $table->enum('type', ['Intraday', 'Swing', 'Positional', 'Long Term', 'Options', 'Futures']);
            $table->decimal('entry', 12, 2);
            $table->decimal('exit', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('pnl', 12, 2); // P/L
            $table->text('reason')->nullable();
            $table->text('mistake')->nullable();
            $table->timestamps();

            $table->index('trade_date');
            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
