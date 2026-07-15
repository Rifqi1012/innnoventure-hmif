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
        Schema::create('ui_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_id')->constrained('tims')->onDelete('cascade');
            $table->string('email_ketua')->unique();
            $table->string('judul_proyek');
            $table->string('link_figma')->nullable();
            $table->string('ppt')->nullable(); // PowerPoint
            $table->string('pdf')->nullable(); // PDF Proposal/Doc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_progress');
    }
};
