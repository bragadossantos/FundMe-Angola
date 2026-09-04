<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('Kz');
            $table->enum('status', [
                'pending',
                'processing',
                'paid',
                'failed',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->string('payment_method')->default('sandbox');
            $table->string('payment_reference')->nullable()->index();
            $table->boolean('is_anonymous')->default(false);
            $table->text('donor_message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
