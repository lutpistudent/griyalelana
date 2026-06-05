<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $table->string('room_number', 20)->unique();
            $table->integer('floor');
            $table->string('position', 100)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'inactive'])->default('available')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['room_type_id', 'status'], 'idx_availability');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
