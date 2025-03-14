<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lottery_id')->constrained('lotteries')->onDelete('cascade');
            $table->foreignId('dashboard_id')->constrained('lottery_dashboards')->onDelete('cascade');
            $table->string('winning_number');
            $table->text('additional_info')->nullable(); // Optional field for any extra info
            $table->foreignId('created_by')->nullable()->constrained('admin'); 
            $table->foreignId('updated_by')->nullable()->constrained('admin'); 
            $table->foreignId('deleted_by')->nullable()->constrained('admin'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('results');
    }
}
