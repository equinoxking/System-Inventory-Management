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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name' , 60);
            $table->string('office', 10);
            $table->string('position', 20);
            $table->string('email' , 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username', 30)->unique();
            $table->string('password' , 120);
            $table->string('role', 15);
            $table->string('status', 15);
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
