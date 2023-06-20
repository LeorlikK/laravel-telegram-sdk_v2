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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tab_id')->constrained('tabs', 'id')->onDelete('cascade');
            $table->unsignedInteger('parentId')->default(0);
            $table->string('name');
            $table->text('caption')->nullable();
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
        Schema::dropIfExists('folders');
    }
};
