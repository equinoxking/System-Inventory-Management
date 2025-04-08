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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreignId('status_id')->references('id')->on('transaction_statuses')->onDelete('cascade');
            $table->foreignId('released_by')->references('id')->on('clients')->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->dateTime('request_aging')->nullable();
            $table->dateTime('released_aging')->nullable();
            $table->time('released_time')->nullable();
            $table->date('approved_date')->nullable();
            $table->time('approved_time')->nullable();
            $table->string('reason', 255)->nullable();
            $table->string('remark');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
