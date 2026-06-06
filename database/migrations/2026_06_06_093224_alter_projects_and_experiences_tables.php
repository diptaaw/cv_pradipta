<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('gallery_images')->nullable()->after('thumbnail');
            $table->string('year')->nullable()->after('gallery_images');
            $table->date('start_date')->nullable()->after('year');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('category')->nullable()->after('end_date');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('year');
            $table->date('end_date')->nullable()->after('start_date');
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
