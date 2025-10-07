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
        Schema::create('digit_result', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lottery_id')->constrained('lotteries')->onDelete('cascade');
            $table->foreignId('digit_dashboard_id')->constrained('digit_lottery_dashboards')->onDelete('cascade');
            $table->string('winning_number');
            $table->text('additional_info')->nullable(); // Optional field for any extra info
            $table->foreignId('created_by')->nullable()->constrained('admin');
            $table->foreignId('updated_by')->nullable()->constrained('admin');
            $table->foreignId('deleted_by')->nullable()->constrained('admin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digit_result');
    }
};
