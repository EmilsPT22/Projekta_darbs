<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('daily_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('internship_id')->constrained()->onDelete('cascade');
        $table->foreignId('theme_id')->constrained()->onDelete('cascade');

        $table->date('date');
        $table->string('location');
        $table->string('time_from');
        $table->string('time_to');
        $table->integer('credit_hours');
        $table->string('duration');
        $table->integer('grade')->nullable();
        $table->text('intern_comment')->nullable();
        $table->text('org_supervisor_comment')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_entries');
    }
};
