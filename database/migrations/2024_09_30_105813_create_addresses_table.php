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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('city',50)->nullable();
            $table->string('district',100)->nullable();
            $table->string('building_number',20)->nullable();
            $table->string('apartment_number',20)->nullable();
            $table->foreignId('guardian_id')->nullable()->constrained('guardians','user_id')->cascadeOnDelete(); //each guardian has on address (one to one relation)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
