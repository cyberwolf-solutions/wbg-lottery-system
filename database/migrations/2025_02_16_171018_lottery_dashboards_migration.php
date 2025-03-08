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
        Schema::create('lottery_dashboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lottery_id')->constrained('lotteries')->onDelete('cascade'); // Foreign key to lotteries table
            $table->string('dashboard');
            $table->decimal('price', 10, 2); // Assuming price is a decimal
            $table->date('date'); // Lottery draw date
            $table->string('draw'); // Draw type (could be like 'first', 'second', etc.)
            $table->string('draw_number'); // Draw number or code
            $table->json('winning_numbers'); // Winning numbers stored as JSON (e.g., ["00", "01", "02", ..., "99"])
            $table->string('status')->default('active');
            $table->softDeletes(); // Enable soft delete
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_dashboards');
    }
};


