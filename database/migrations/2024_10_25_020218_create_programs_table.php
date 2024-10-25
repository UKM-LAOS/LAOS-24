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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->string('program_title')->unique();
            $table->string('program_slug');
            $table->string('activity_title');
            $table->longText('content');
            $table->double('latitude');
            $table->double('longitude');
            $table->date('open_regis_panitia');
            $table->date('close_regis_panitia');
            $table->date('open_regis_peserta');
            $table->date('close_regis_peserta');
            $table->string('gform_panitia');
            $table->string('gform_peserta');
            $table->json('program_schedules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
