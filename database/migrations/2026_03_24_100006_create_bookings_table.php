<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->date('check_in_date');
            $table->integer('duration_years')->default(1);
            $table->enum('payment_option', ['with_dp', 'direct_checkin']);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('dp_amount', 12, 2)->nullable();
            $table->enum('identity_type', ['ktp', 'ktm', 'other']);
            $table->string('identity_number', 50);
            $table->string('emergency_contact', 20)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('dp_expires_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
