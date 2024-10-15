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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users','id')->cascadeOnDelete(); //Deal with this as a primary key
            $table->string('code',20)->nullable()->unique();
            $table->string('SSN',50)->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender',['ذكر','أنثي'])->nullable();
            $table->integer('rank')->nullable();
            $table->string('relationship')->nullable();
            $table->string('phone',15)->nullable();
            $table->enum('status',['مقبول','مرفوض','قيد الانتظار','متخرج','تم حذفه','خارج المدرسه'])->default('قيد الانتظار');
            $table->foreignId('guardian_id')->nullable()->constrained('guardians','user_id')->cascadeOnDelete(); //each guardian has many students (one to many relation)
            $table->foreignId('current_grade_id')->nullable()->constrained('grades')->onDelete('set null'); //A student has a single current grade, and a grade can be assigned to many students.
            $table->foreignId('current_term_id')->nullable()->constrained('terms')->onDelete('set null'); //The current_term_id in the students table will directly track each student's current term.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
