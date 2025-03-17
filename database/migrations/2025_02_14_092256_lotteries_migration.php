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
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->unique();
            $table->string('image');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade'); // Assuming 'users' table exists
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade'); // Nullable for cases where no update happens yet
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade'); // Nullable for soft deletes
            $table->softDeletes(); // Enable soft delete
            $table->timestamps(); // Created at and updated at
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('users');

        Schema::table('lotteries', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the 'deleted_at' column
        });
    }
};
