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
            $table->string('dashboardType');
            $table->decimal('price', 10, 2); // Assuming price is a decimal
            $table->date('date'); // Lottery draw date
            $table->string('draw'); // Draw type (could be like 'first', 'second', etc.)
            $table->string('draw_number'); // Draw number or code
            $table->json('winning_numbers'); 
            $table->string('color');
            $table->string('status')->default('active');
            $table->softDeletes(); 
            $table->timestamps(); 
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
