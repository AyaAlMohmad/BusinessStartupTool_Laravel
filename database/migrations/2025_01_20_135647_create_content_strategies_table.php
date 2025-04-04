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
        Schema::create('content_strategies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('marketing_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('business_id'); 
            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');

            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->string('frequency')->nullable();
            $table->string('responsible_person')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_strategies');
    }
};
