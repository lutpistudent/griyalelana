<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_type_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->cascadeOnDelete();
            $table->string('photo_url', 500);
            $table->string('cloudinary_public_id', 255)->nullable();
            $table->string('caption', 255)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['room_type_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_type_photos');
    }
};
