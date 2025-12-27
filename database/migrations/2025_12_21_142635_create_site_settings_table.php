<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('site_description');
            $table->json('footer_social_links')->nullable();
            $table->string('copyright_name');
            $table->string('copyright_text');
            $table->string('designer_name');
            $table->string('designer_url');
            $table->string('distributor_name');
            $table->string('distributor_url');
            $table->string('distributed_by_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};