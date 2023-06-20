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
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('folders', 'id')->onDelete('cascade');
            $table->string('text')->nullable();
            $table->string('callback')->nullable();
            $table->string('media')->nullable();
            $table->unsignedInteger('sorted_id');
            $table->timestamp('display')->nullable();
            $table->unsignedInteger('visibility')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buttons');
    }
};
