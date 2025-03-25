<?php

// database/migrations/xxxx_xx_xx_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); // Reference to Wallet table
            $table->decimal('amount', 15, 2);
            $table->decimal('picked_number', 15)->nullable();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer','refund' ,'Number pick','winning','Affiliate Commission']); // Type of transaction
            $table->foreignId('lottery_id')->constrained('lotteries')->onDelete('cascade')->nullable(); // Add foreign key for lottery
            $table->foreignId('lottery_dashboard_id')->constrained('lottery_dashboards')->onDelete('cascade')->nullable(); // Add foreign key for lottery dashboard
            $table->date('transaction_date'); 
            $table->softDeletes(); // Soft delete column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

