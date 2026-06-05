<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('room_categories')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('price_per_year', 12, 2);
            $table->boolean('has_ac')->default(false);
            $table->enum('bathroom_type', ['inside', 'outside']);
            $table->string('bed_size', 50)->nullable();
            $table->decimal('room_size', 5, 2)->nullable();
            $table->json('facilities')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['has_ac', 'bathroom_type'], 'idx_filter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
