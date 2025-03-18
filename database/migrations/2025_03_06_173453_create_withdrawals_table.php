<?php

// database/migrations/xxxx_xx_xx_create_withdrawals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); // Reference to Wallet table
            $table->decimal('amount', 15, 2);
            $table->string('withdrawal_type')->nullable(); // Optional description
            $table->string('decline_reason')->nullable(); // Optional description
            $table->date('withdrawal_date');
            $table->tinyInteger('status')->default(0); 
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
        Schema::dropIfExists('withdrawals');
    }
}
