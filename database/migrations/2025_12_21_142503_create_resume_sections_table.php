<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'summary', 'education', 'experience'
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('period')->nullable();
            $table->string('institution')->nullable();
            $table->text('description');
            $table->json('details')->nullable(); // Store as JSON array
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_sections');
    }
};