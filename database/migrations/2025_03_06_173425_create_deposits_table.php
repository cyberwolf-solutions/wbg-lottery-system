<?php

// database/migrations/xxxx_xx_xx_create_deposits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade'); // Reference to Wallet table
            $table->decimal('amount', 15, 2);
            $table->string('deposite_type')->nullable(); // Optional description
            $table->date('deposit_date'); 
            $table->string('reference')->nullable(); // Reference number
            $table->string('decline_reason')->nullable(); // Reference number
            $table->string('image')->nullable(); // Store image file path
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
        Schema::dropIfExists('deposits');
    }
}
