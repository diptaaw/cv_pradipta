<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->json('technologies')->nullable();
            $table->string('project_link')->nullable();
            $table->string('github_link')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('archived')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};