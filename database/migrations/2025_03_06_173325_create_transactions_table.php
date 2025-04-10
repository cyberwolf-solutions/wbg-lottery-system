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
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); 
            $table->decimal('amount', 15, 2);
            $table->string('picked_number', 15)->nullable();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer','refund' ,'Number pick','winning','Affiliate Commission','Admin Deposit','Admin Deduction']); 
            $table->foreignId('lottery_id')->nullable()->constrained('lotteries')->onDelete('cascade'); 
            $table->foreignId('lottery_dashboard_id')->nullable()->constrained('lottery_dashboards')->onDelete('cascade'); 
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

