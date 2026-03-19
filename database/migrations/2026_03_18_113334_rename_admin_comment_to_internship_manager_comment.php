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
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->renameColumn('admin_comment', 'internship_manager_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->renameColumn('internship_manager_comment', 'admin_comment');
        });
    }
};
