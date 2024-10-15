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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name',30);
            $table->string('email')->nullable();
            $table->string('message',300)->nullable();
            $table->string('phone',15)->nullable();
            $table->string('resume',255)->nullable();
            $table->string('title',20)->nullable();
            $table->enum('status',['جديدة','مفتوحة'])->default('جديدة');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
