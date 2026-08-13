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
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropForeign(['webdev_progress_id']);
            $table->dropUnique(['webdev_progress_id', 'aspek_penilaian_id', 'juri_id']);
            
            $table->foreignId('webdev_progress_id')->nullable()->change();
            $table->foreign('webdev_progress_id')->references('id')->on('webdev_progress')->onDelete('cascade');

            $table->foreignId('ui_progress_id')->nullable()->after('webdev_progress_id')->constrained('ui_progress')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropForeign(['ui_progress_id']);
            $table->dropColumn('ui_progress_id');
            $table->foreignId('webdev_progress_id')->nullable(false)->change();
            $table->unique(['webdev_progress_id', 'aspek_penilaian_id', 'juri_id']);
        });
    }
};
