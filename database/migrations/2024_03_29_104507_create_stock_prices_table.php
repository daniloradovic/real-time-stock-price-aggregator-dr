<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->decimal('open', 8, 4);
            $table->decimal('high', 8, 4);
            $table->decimal('low', 8, 4);
            $table->bigInteger('volume');
            $table->decimal('price', 8, 4);
            $table->decimal('previous_close', 8, 4);
            $table->decimal('change', 8, 4);
            $table->decimal('change_percent', 8, 4);
            $table->dateTime('date')->default(now());
            $table->string('symbol');
            $table->timestamps();
            // Add indexes
            $table->index('company_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_prices');
    }
};
