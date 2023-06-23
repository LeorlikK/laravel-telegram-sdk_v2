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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tg_id')->unique();
            $table->string('username')->nullable()->default(null);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('language');
            $table->foreignId('role_id')->constrained('roles', 'id');
            $table->string('mail')->nullable()->default(null);
            $table->string('number')->nullable()->default(null);
            $table->string('edit')->nullable()->default(null);
            $table->boolean('is_premium')->nullable()->default(false);
            $table->boolean('is_blocked')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
