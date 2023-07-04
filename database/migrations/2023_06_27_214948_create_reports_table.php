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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('to_whom')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->string('theme');
            $table->text('message');
            $table->boolean('state');
            $table->string('type')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
