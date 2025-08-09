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
        Schema::create('podcast_audio_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained()->onDelete('cascade');
            $table->foreignId('script_id')->constrained('podcast_scripts')->onDelete('cascade');
            $table->string('file_path');
            $table->string('voice_id')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->unique(['podcast_id', 'order']);
            $table->string('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_audio_segments');
    }
};
