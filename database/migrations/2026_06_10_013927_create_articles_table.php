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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); 
            $table->bigInteger('love')->default(0);
            $table->boolean('is_premium')->default(false);
            $table->longText('content');
            $table->enum('status', ['published', 'draft']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->index(['status', 'is_premium']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
