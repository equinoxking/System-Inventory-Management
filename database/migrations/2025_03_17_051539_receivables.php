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
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('control_number')->unique()->nullable();
            $table->string('delivery_type', 50);
            $table->integer('received_quantity');
            $table->integer('received_day', 5);
            $table->integer('received_month', 30);
            $table->integer('received_year', 10);
            $table->string('remark', 30);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
