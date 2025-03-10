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
        Schema::create('picked_numbers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('lottery_id')->constrained('lotteries')->onDelete('cascade'); // Foreign key to lotteries table
            $table->foreignId('lottery_dashboard_id')->constrained('lottery_dashboards')->onDelete('cascade'); // Foreign key to lottery_dashboards
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('picked_number');
            $table->string('status')->default('pending');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picked_numbers');
    }
};
