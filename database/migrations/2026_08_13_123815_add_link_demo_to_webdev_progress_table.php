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
        Schema::table('webdev_progress', function (Blueprint $table) {
            $table->string('link_demo')->nullable()->after('link_github');
            $table->text('catatan')->nullable()->after('judul_proyek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webdev_progress', function (Blueprint $table) {
            $table->dropColumn(['link_demo', 'catatan']);
        });
    }
};
