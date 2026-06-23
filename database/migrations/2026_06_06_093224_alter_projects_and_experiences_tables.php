<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('gallery_images')->nullable();
            $table->string('year')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('category')->nullable();
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['gallery_images', 'year', 'start_date', 'end_date', 'category']);
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
