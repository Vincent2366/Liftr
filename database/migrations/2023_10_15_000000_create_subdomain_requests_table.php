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
        Schema::create('subdomain_requests', function (Blueprint $table) {
            $table->id();
            $table->string('subdomain')->unique();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            // Add this after the table is created
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdomain_requests');
    }
};
