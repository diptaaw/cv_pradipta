<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');              // e.g. project_created, experience_deleted
            $table->string('title');             // Human-readable action title
            $table->text('description')->nullable(); // Detail text
            $table->string('reference_type')->nullable(); // e.g. project, experience, resume
            $table->unsignedBigInteger('reference_id')->nullable(); // FK to referenced model
            $table->boolean('is_read')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['is_read', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
