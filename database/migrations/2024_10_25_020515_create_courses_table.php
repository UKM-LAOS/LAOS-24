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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mentor_id');
            $table->foreign('mentor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('course_category_id')->constrained()->onDelete('cascade');
            $table->string('title')->unique();
            $table->string('slug');
            $table->enum('type', ['free', 'premium']);
            $table->enum('level', ['all-level', 'beginner', 'intermediate', 'advanced']);
            $table->bigInteger('price');
            $table->longText('content');
            $table->boolean('is_draft')->default(true);
            $table->text('drive_url_resource')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
