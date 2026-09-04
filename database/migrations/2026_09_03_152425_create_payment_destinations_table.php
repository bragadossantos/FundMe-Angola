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
        Schema::create('payment_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->enum('destination_type', ['hospital_direct', 'beneficiary_transfer', 'split_payment']);
            $table->string('institution_or_payee_name');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('mobile_money_number')->nullable();
            $table->string('invoice_reference')->nullable();
            $table->decimal('authorized_amount', 12, 2);
            $table->text('private_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_destinations');
    }
};
