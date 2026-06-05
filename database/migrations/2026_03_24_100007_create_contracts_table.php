<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
            $table->string('contract_number', 50)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_years');
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_option', ['with_dp', 'direct_checkin']);
            $table->string('contract_pdf_url', 500)->nullable();
            $table->enum('status', ['active', 'completed', 'terminated', 'extended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'end_date'], 'idx_expiring');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
