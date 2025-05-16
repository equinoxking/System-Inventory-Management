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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('no action');
            $table->integer('request_item');
            $table->integer('request_quantity');
            $table->integer('request_day');
            $table->integer('request_month');
            $table->integer('request_year');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
